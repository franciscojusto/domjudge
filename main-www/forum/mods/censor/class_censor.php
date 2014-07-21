<?php
/*-----------------------------------------------------------------------------
	Censor Suite - A phpBB Add-On
  ----------------------------------------------------------------------------
	class_censor.php
	File Version: 3.0.0
	Begun: March 8, 2009
	Last Modified: November 30, 2011
  ----------------------------------------------------------------------------
	Copyright 2011 by Jeremy Rogers.
	License: GNU General Public License v2
-----------------------------------------------------------------------------*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class Censor_Suite
{
	var $word_replaces		= array();
	var $name_replaces		= array();
	var $whitelist			= array();
	var $name_subs_built	= false;
	var $word_subs_built	= false;
	var $link_id			= 0;

	// Censor Block vars
	var $block_enable	= true;
	var $block_init		= false;
	var $censors;
	var $errors			= array();
	var $triggered		= false;
	var $triggers		= array();

	function Censor_Suite()
	{
		global $config, $table_prefix;

		define('WORDS_SUBSTITUTIONS_TABLE',	$table_prefix . 'words_substitutions');
		define('WORDS_WHITELIST_TABLE',	$table_prefix . 'words_whitelist');

		// Force disable of block functions in admin panel. We'll trust that
		// anyone with admin access won't violate the censor rules there.
		$this->block_enable = (defined('IN_ADMIN')) ? false: ( !empty($config['censor_block']) ) ? true: false;
	}

	/*
		Initiate Censor Block features
	*/
	function init_block()
	{
		global $config, $cache, $user;
		if( !$this->block_enable || $this->block_init )
		{
			// Prevent processing more than once, or if disabled
			$this->block_init = true;
			return;
		}
		$this->block_init = true;
		$this->censors	= $cache->obtain_word_list();
		if( empty($this->censors) )
		{
			// Without censors, we don't have to check anything later
			$this->block_enable = false;
			return;
		}
		$this->triggered	= request_var('censor_triggered', false);
		$user->add_lang('mods/info_acp_censor');
	}

	/*
		Scan content for censored words and create an error message preview if
		any are found.

		$content		- Content to parse.
		$label			- Language key for content; used in error messages.
		$strip_bbcode	- Should the check remove BBCode to prevent bypassing?
	*/
	function check_field($mode, $content, $label, $strip_bbcode = false)
	{
		global $config, $user, $phpbb_root_path, $phpEx;

		$this->init_block();
		if( !$this->block_enable || empty($content) )
		{
			return;
		}

		if( $strip_bbcode )
		{
			if( !class_exists('parse_message') )
			{
				include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
			}
			$message_parser = new parse_message($content);
			// Assuming BBCode, smilies, and magic links are allowed here
			// so that we can strip them out
			$message_parser->parse(true, true, true);

			// Recreating strip_bbcode() here, because it replaces BBCodes with a space.
			// That allows "te[i]s[/i]t" to become "te s t" which would be a censor bypass
			// if "test" is a banned word
			$uid = $message_parser->bbcode_uid;
			$message_parser->message = preg_replace("#\[\/?[a-z0-9\*\+\-]+(?:=(?:&quot;.*&quot;|[^\]]*))?(?::[a-z])?(\:$uid)\]#", '', $message_parser->message);

			$match = get_preg_expression('bbcode_htm');
			$replace = array('\1', '\1', '\2', '\1', '', '');

			$content = preg_replace($match, $replace, $message_parser->message);
			unset($message_parser); // Free some memory
		}

		// PHP 5.2.0+ has limit that we need to respect.
		// If we don't, site pages might break.
		if( $config['censor_whitelist'] )
		{
			$backtrack_limit = @ini_get('pcre.backtrack_limit');
			if( $backtrack_limit !== false && utf8_strlen($content) >= ($backtrack_limit / 2) )
			{
				// Post is too long to check with whitelist
				$config['censor_whitelist'] = false;
			}
		}
		if( $config['censor_whitelist'] )
		{
			// Check using whitelist
			$censored = preg_replace_callback($this->censors['match'], array(&$this, 'replace_with_whitelist'), $content);
		}
		else
		{
			// Check without whitelist
			$censored = preg_replace($this->censors['match'], '<span class="' . $config['censor_block_hilight'] . '">\\1</span>', $content);
		}

		if( $censored !== $content )
		{
			// At least one or more words are censored.
			// Do some processing to make the preview pretty
			$censored		= bbcode_nl2br($censored);
			$censored		= smiley_text($censored, OPTION_FLAG_SMILIES);
			$label			= ( isset($user->lang[$label]) ) ? $user->lang[$label]: $label;
			$this->errors[]	= $user->lang('CENSOR_BLOCK_ERROR_FIELD', $label, '<span class="' . $config['censor_block_class'] . '">' . $censored . '</span>');

			$this->record_triggers($mode, $content, $label);
		}
		elseif( !$strip_bbcode )
		{
			// Passed first check, try again with BBCode stripped out
			$this->check_field($mode, $content, $label, true);
		}
	}

	/*
		Record the pieces of content that trigger censors.

		$content		- Content to parse.
		$label			- Language key for content; used in error messages.
		$mode			- The type of content being parsed (post/sig/etc)
	*/
	function record_triggers($mode, $content, $label)
	{
		global $config;

		if( $config['censor_logs'] || $config['censor_report_posts'] || $config['censor_report_pms'] )
		{
			if( !isset($this->triggers[$mode]) )
			{
				$this->triggers[$mode] = array();
			}
			if( !isset($this->triggers[$mode][$label]) )
			{
				$this->triggers[$mode][$label] = array();
			}
			foreach( $this->censors['match'] as $k => $match )
			{
				$matches = array();
				preg_match_all($match, $content, $matches);
				if( !empty($matches[0]) )
				{
					for( $i = 0, $num = count($matches[0]) ; $i < $num ; $i++ )
					{
						if( isset($this->whitelist[$matches[0][$i]]) )
						{
							// Skip matches that are whitelisted
							// TODO: Optionally record these somewhere?
							continue;
						}
						$this->triggers[$mode][$label][] = $matches[0][$i];
					}
				}
			}
		}
	}

	function get_whitelist()
	{
		global $db, $cache, $config;
		if( !empty($this->whitelist) )
		{
			return;
		}
		if (($this->whitelist = $cache->get('_word_whitelist')) === false)
		{
			$this->whitelist = array();
			$sql = 'SELECT * FROM ' . WORDS_WHITELIST_TABLE;
			$result = $db->sql_query($sql);
			while( $row = $db->sql_fetchrow($result) )
			{
				// Store the word as the key so we can use isset() instead of
				// in_array() later
				$this->whitelist[$row['whitelist_word']] = $row['whitelist_id'];
			}
			$db->sql_freeresult($result);

			$cache->put('_word_whitelist', $this->whitelist);
		}
	}

	function destroy_whitelist()
	{
		global $cache;
		// Clear both the phpBB cache and the current script's record.
		$cache->destroy('_word_whitelist');
		$this->whitelist = array();
	}

	/*
		Callback to check if content matching the censors is on the whitelist.

		$matches		- The content that matched the censors.
	*/
	function replace_with_whitelist($matches)
	{
		global $config;
		$this->get_whitelist();

		// Is match on the whitelist?
		$to_check = strtolower($matches[1]);
		if( isset($this->whitelist[$to_check]) )
		{
			// Yes, so we want the replacement to be the match
			return $matches[1];
		}
		else
		{
			// No, handle as normal censors
			return '<span class="' . $config['censor_block_hilight'] . '">' . $matches[1] . '</span>';
		}
	}

	/*
		Interface for Censor Block features. Handles mode-specific tasks for
		checking submission fields and passes error messages back to phpBB's
		internal error handling.

		$mode		- Submission type: post, signature, etc.
		$error		- phpBB's error handling array.
		$data		- $data to be parsed.
		$validate	- Additional info for identifying pieces of $data to parse.
	*/
	function block($mode, &$error, $data = '', $validate = '')
	{
		global $config, $user;

		$this->init_block();
		if( !$this->block_enable || empty($data) )
		{
			return;
		}

		switch( $mode )
		{
			case 'post':
				global $message_parser;
				$fields = array(
					array('label' => 'SUBJECT',			'var' => 'data',	'field' => 'post_subject'),
					array('label' => 'USERNAME',		'var' => 'data',	'field' => 'username'),
					array('label' => 'EDIT_REASON',		'var' => 'data',	'field' => 'post_edit_reason'),
					array('label' => 'POLL_QUESTION',	'var' => 'data',	'field' => 'poll_title'),
					array('label' => 'POLL_OPTIONS',	'var' => 'data',	'field' => 'poll_option_text'),
					array('label' => 'MESSAGE_BODY',	'var' => 'message_parser',	'property' => 'message',	'field' => ''),
					array('label' => 'FILE_COMMENT',	'var' => 'message_parser',	'property' => 'filename_data',	'field' => 'filecomment'),
				);

				for( $i = 0, $num = count($fields) ; $i < $num ; $i++ )
				{
					$label	= $fields[$i]['label'];
					$var	= $fields[$i]['var'];
					if( is_array($$var) && !empty($fields[$i]['field']) )
					{
						$var .= "['" . $fields[$i]['field'] . "']";
						if( empty($$var) )
						{
							continue;
						}
						$this->check_field($mode, $$var, $label);
					}
					elseif( is_object($$var) && !empty($fields[$i]['property']) )
					{
						if( is_array($$var->$fields[$i]['property']) && !empty($fields[$i]['field']) )
						{
							$this->check_field($mode, ${$var}->{$fields[$i]['property']}[$fields[$i]['field']], $label);
						}
						elseif( !empty($$var->$fields[$i]['property']) )
						{
							$this->check_field($mode, $$var->$fields[$i]['property'], $label);
						}
					}
				}

				// This one needs to run through several array entries
				if( !empty($message_parser->attachment_data) )
				{
					foreach($message_parser->attachment_data as $k=>$v)
					{
						$this->check_field($mode, $v['attach_comment'], 'FILE_COMMENT');
					}
				}
			break;

			case 'signature':
				if( !empty($data) )
				{
					$this->check_field($mode, $data, 'SIGNATURE');
				}
			break;
			case 'profile':
				foreach($validate as $k=>$v)
				{
					if( !is_array($v[0]) )
					{
						$v = array($v);
					}

					foreach($v as $validate_info)
					{
						if( $validate_info[0] != 'string' )
						{
							continue;
						}

						$this->check_field($mode, $data[$k], strtoupper($k));
					}
				}
			break;
			case 'custom_profile':
				switch( $validate )
				{
					case FIELD_STRING:
					case FIELD_TEXT:

						$this->check_field($mode, $data['value'], $data['label']);
					break;
				}
			break;
		}

		if (sizeof($this->errors))
		{
			$error[]			= $user->lang('CENSOR_BLOCK_ERROR_DESC', $config['board_email']);
			$error				= array_merge($error, $this->errors);
			$this->errors		= array();
			$this->triggered	= true;
			$this->log_censor();
		}
	}

	/*
		Output a hidden form field for triggering automatic message reporting.
	*/
	function get_hidden(&$s_hidden)
	{
		if( $this->triggered )
		{
			$s_hidden .= build_hidden_fields(array('censor_triggered' => true));
		}
	}

	/*
		Create a log report for prevented censor activity
	*/
	function log_censor()
	{
		global $config, $user;

		if( !$this->triggered || !$config['censor_logs'] )
		{
			return;
		}

		foreach( $this->triggers as $mode => $triggers )
		{
			$data = array();
			foreach( $triggers as $label => $match )
			{
				$data[] = $label . ': ' . implode(', ', $match);
			}
			add_log('admin', 'CENSOR_BLOCK_LOG', implode(', ', $data));
			/*
			// Logging to moderator/user logs. This provides topic/forum ids, but also makes the
			// logs visible to moderators. Also requires an extra file edit for proper log display.
			// Might enable this at some later point.
			switch( $mode )
			{
				case 'post':
					global $forum_id, $topic_id;
					add_log('mod', $forum_id, $topic_id, 'CENSOR_BLOCK_LOG', implode(', ', $data));
				break;
				case 'signature':
				case 'profile':
				case 'custom_profile':
					add_log('user', $user->data['user_id'], 'CENSOR_BLOCK_LOG', implode(', ', $data));
				break;
			}
			*/
		}
	}

	/*
		Automatically create a moderator report for messages that triggered
		Censor Block at some point during the submission process. This allows
		admins to monitor censored activity and catch new methods of censor
		bypassing.
	*/
	function report($data)
	{
		global $config, $user, $db;

		if( !$this->triggered )
		{
			return;
		}

		$post_id	=  ( isset($data['post_id']) )	? $data['post_id']: 0;
		$pm_id		=  ( isset($data['msg_id']) )	? $data['msg_id']: 0;

		if( ($post_id && !$config['censor_report_posts']) || ($pm_id && !$config['censor_report_pms']) )
		{
			return;
		}

		$sql = 'SELECT *
			FROM ' . REPORTS_REASONS_TABLE . '
			WHERE reason_id = ' . $config['censor_report_reason'];
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$row )
		{
			return;
		}

		$reason_id = $row['reason_id'];

		$sql_ary = array(
			'reason_id'		=> (int) $reason_id,
			'post_id'		=> $post_id,
			'pm_id'			=> $pm_id,
			'user_id'		=> (int) $user->data['user_id'],
			'user_notify'	=> 0,
			'report_closed'	=> 0,
			'report_time'	=> (int) time(),
			'report_text'	=> $user->lang['CENSOR_REPORT']
		);

		$sql = 'INSERT INTO ' . REPORTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		$report_id = $db->sql_nextid();

		if ($post_id)
		{
			$sql = 'UPDATE ' . POSTS_TABLE . '
				SET post_reported = 1
				WHERE post_id = ' . $post_id;
			$db->sql_query($sql);

			$sql = 'SELECT topic_reported
				FROM ' . TOPICS_TABLE . "
				WHERE topic_id = {$data['topic_id']}";
			$result = $db->sql_query($sql);
			$topic_reported = $db->sql_fetchfield('topic_reported');
			$db->sql_freeresult($result);

			if (!$topic_reported)
			{
				$sql = 'UPDATE ' . TOPICS_TABLE . '
					SET topic_reported = 1
					WHERE topic_id = ' . $data['topic_id'] . '
						OR topic_moved_id = ' . $data['topic_id'];
				$db->sql_query($sql);
			}
		}
		else
		{
			$sql = 'UPDATE ' . PRIVMSGS_TABLE . '
				SET message_reported = 1
				WHERE msg_id = ' . $pm_id;
			$db->sql_query($sql);

			$sql_ary = array(
				'msg_id'		=> $pm_id,
				'user_id'		=> ANONYMOUS,
				'author_id'		=> (int) $user->data['user_id'],
				'pm_deleted'	=> 0,
				'pm_new'		=> 0,
				'pm_unread'		=> 0,
				'pm_replied'	=> 0,
				'pm_marked'		=> 0,
				'pm_forwarded'	=> 0,
				'folder_id'		=> PRIVMSGS_INBOX,
			);

			$sql = 'INSERT INTO ' . PRIVMSGS_TO_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
		}
	}

	/*
		Custom error display. For use in any situation where an existing error
		display cannot be accessed. (Mainly for non-phpBB applications or
		integration with other modifications.)
	*/
	function show_error()
	{
		global $template;
		if( !empty($this->errors) )
		{
			$msg = implode('<br /><br />', $this->errors);
			$template->assign_vars(array(
				// BLOCK versions are depreciated, but kept for compatibility.
				'CENSOR_BLOCK_ERR'	=> true,
				'CENSOR_BLOCK_MSG'	=> $msg,
				'CENSOR_SUITE_ERR'	=> true,
				'CENSOR_SUITE_MSG'	=> $msg,
			));
			return true;
		}
		return false;
	}

	// Replacement for preg_quote() to escape - character prior to PHP 5.3.0
	function preg_escape($word, $delim = '#')
	{
		$word = preg_quote($word, $delim);
		if( !version_compare(PHP_VERSION, '5.3.0', '>=') && strpos($word, '-') !== false)
		{
			// Remove - from the word and add it on to the end
			// Prior to PHP 5.3.0, escaping - in the middle of string
			// causes regex range compile errors
			$word = str_replace('-', '', $word);
			$word .= '-';
		}
		return $word;
	}

	/*
		Parses bypass detection settings into patterns and classes for insertion
		into word censors. Classes are faster than patterns, but only effective
		for single character substitutions.
	*/
	function build_substitutions($usernames = false)
	{
		global $config, $db;

		if( !$config['censor_substitutes'] || ($usernames && $this->name_subs_built) || (!$usernames && $this->word_subs_built) )
		{
			return;
		}

		$variants = array();
		$sql	= 'SELECT substitution_key, substitution_replace FROM ' . WORDS_SUBSTITUTIONS_TABLE;
		$result	= $db->sql_query($sql);
		while( $row = $db->sql_fetchrow($result) )
		{
			if( !isset($variants[$row['substitution_key']]) )
			{
				$variants[$row['substitution_key']] = array(
					'class'		=> array(),
					'pattern'	=> array(),
				);
			}
			$field = (strlen($row['substitution_replace']) > 1)? 'pattern': 'class';
			$variants[$row['substitution_key']][$field][] .= ( !$usernames ) ? $row['substitution_replace'] : utf8_clean_string($row['substitution_replace']);
		}

		$db->sql_freeresult($result);

		if( !empty($variants) )
		{
			$replaces = ( $usernames ) ? 'name_replaces' : 'word_replaces';
			foreach( $variants as $key => $pieces )
			{
				$pattern = '';
				if( !empty($pieces['class']) )
				{
					$pattern .= '[';
					if( strlen($key) == 1 )
					{
						$pattern .= $this->preg_escape($key);
					}
					$pattern .= $this->preg_escape(implode('', $pieces['class'])) . ']';
				}

				if( !empty($pieces['pattern']) )
				{
					if( empty($pattern) )
					{
						$pattern = '(?:' . preg_quote($key, '#') . '|';
					}
					else
					{
						$pattern = '(?:' . $pattern . '|';
						if( strlen($key) > 1 )
						{
							$pattern .= preg_quote($key, '#') .'|';
						}
					}
					for( $i = 0, $num = count($pieces['pattern']) ; $i < $num ; $i++ )
					{
						$pattern .= ( $i > 0 ) ? '|' : '';
						$pattern .= preg_quote($pieces['pattern'][$i], '#');
					}
					$pattern .= ')';
				}
				$this->{$replaces}[$key]	= $pattern;
			}
			unset($variants);
		}

		( $usernames ) ? $this->name_subs_built = true : $this->word_subs_built = true;
	}

	/*
		Returns the flag for studying a regular expression.
	*/
	function get_flag()
	{
		global $config;
		if( !$config['censor_study'] )
		{
			return '';
		}
		return 'S';
	}

	function convert_word(&$word, $non_unicode = false)
	{
		if( empty($this->word_replaces) || empty($word) )
		{
			return $word;
		}

		// Insert character replacements, possibly including escaped asterisks
		$word = strtr($word, $this->word_replaces);

		// Extra step for non-unicode situations on phpBB 3.0.8 or earlier.
		// Not used with phpBB 3.0.9 or later, but remains for compatibility.
		if( $non_unicode )
		{
			// Replace only unescaped literal asterisks
			$word = preg_replace('/(?<!\\\\)\*/', '\S*?', $word);
			$word = '#(?<!\S)(' . $word . ')(?!\S)#iu' . $this->get_flag();
		}

		return $word;
	}

	function convert_usernames(&$usernames)
	{
		if( empty($this->name_replaces) || empty($usernames) )
		{
			return;
		}

		for( $i = 0, $num = count($usernames) ; $i < $num ; $i++ )
		{
//			$usernames[$i] = str_replace($this->matches, $this->patterns, $usernames[$i]);
			$usernames[$i] = strtr($usernames[$i], $this->name_replaces);
		}
	}

	/*	Currently unused
	// part of possible future Link Assistant feature
	function display_link_assist()
	{
		global $config, $template;

		define('CENSOR_MOOTOOLS_LOCAL',		1);
		define('CENSOR_JQUERY_LOCAL',		2);
		define('CENSOR_MOOTOOLS_REMOTE',	3);
		define('CENSOR_JQUERY_REMOTE',		4);

		define('CENSOR_MOOTOOLS_URL',		'https://ajax.googleapis.com/ajax/libs/mootools/1.3.1/mootools-yui-compressed.js');
		define('CENSOR_JQUERY_URL',			'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js');


		// Don't need to do anything if BBCode has not been processed
		if( !$config['censor_link_hovers'] || !class_exists('bbcode') )
		{
			return;
		}

		$tpl_vars = array(
			'S_CENSOR_GOOGLE_KEY'	=>	( !empty($config['censor_google_api_key']) ) ? $config['censor_google_api_key'] : '',
			'U_CENSOR_MOOTOOLS'		=>	CENSOR_MOOTOOLS_URL,
			'U_CENSOR_JQUERY'		=>	CENSOR_JQUERY_URL,
		);
		switch( $config['censor_js_library'] )
		{
			case CENSOR_MOOTOOLS_REMOTE:
				$tpl_vars['S_CENSOR_MOOTOOLS_REMOTE']	= true;
			// no break
			case CENSOR_MOOTOOLS_LOCAL:
				$tpl_vars['S_CENSOR_MOOTOOLS']			= true;
			break;
			case CENSOR_JQUERY_REMOTE:
				$tpl_vars['S_CENSOR_JQUERY_REMOTE']		= true;
			// no break
			case CENSOR_JQUERY_LOCAL:
				$tpl_vars['S_CENSOR_JQUERY']			= true;
			break;
		}

		$template->assign_vars($tpl_vars);
	}
	*/
}

?>