<?php
/**
*
* @package acp
* @version $Id$
* @copyright (c) November 30, 2011 Thoul
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_censor
{
	var $u_action;
	var $action;
	var $id;
	var $mode;
	var $form_name;

	var $per_page	= 50; // Items per page in Import Word List

	function main($id, $mode)
	{
		global $user, $template;

		$user->add_lang('acp/posting');
		$this->tpl_name		= 'acp_censor';
 		$this->form_name	= 'acp_censor';
		add_form_key($this->form_name);

		$this->id	= $id;
		$this->mode	= $mode;

		// Set up general vars
		$this->action = request_var('action', '');

		if( method_exists($this, 'page_' . $mode) )
		{
			$this->{'page_' . $mode}();
		}
		else
		{
			trigger_error('UNKNOWN_MODE');
		}

		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'L_TITLE'			=> $user->lang[$this->page_title],
			'L_TITLE_EXPLAIN'	=> ( isset($user->lang[$this->page_title . '_EXPLAIN']) ) ? $user->lang[$this->page_title . '_EXPLAIN'] : '',
		));
	}

	function page_white()
	{
		global $template, $config, $censor_suite, $db, $cache, $user;

		$this->page_title = 'ACP_CENSOR_WHITELIST';

		$this->action	= (isset($_POST['add'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : ((isset($_POST['delete'])) ? 'delete' : $this->action));

		if (!empty($this->action) && !check_form_key($this->form_name))
		{
			trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
		}

		$censor_suite->get_whitelist();
		switch( $this->action )
		{
			case 'add':
				$new_words	= utf8_normalize_nfc(request_var('new_words', '', true));

				if( empty($new_words) )
				{
					$this->trigger_message('ACP_CENSOR_WL_NO_WORDS_SUBMIT', '', false);
					break;
				}
				$new_words = explode("\n", strtolower($new_words));
				for( $i = 0, $num = count($new_words) ; $i < $num ; $i++ )
				{
					$new_words[$i] = htmlspecialchars(trim($new_words[$i]));
					// Check for existence of words
					if( empty($new_words[$i]) || isset($censor_suite->whitelist[$new_words[$i]]) )
					{
						unset($new_words[$i]);
					}
				}
				if( empty($new_words) )
				{
					$this->trigger_message('ACP_CENSOR_WL_NO_WORDS_SUBMIT', '', false);
					break;
				}

				sort($new_words);
				for( $i = 0, $num = count($new_words) ; $i < $num ; $i++ )
				{
					$params = array('whitelist_word' => $new_words[$i]);
					$sql = 'INSERT INTO ' . WORDS_WHITELIST_TABLE . ' ' . $db->sql_build_array('INSERT', $params);
					$db->sql_query($sql);
				}
				$censor_suite->destroy_whitelist();
				add_log('admin', 'ACP_CENSOR_LOG_WL_ADDED', implode(', ', $new_words));
				$this->trigger_message('ACP_CENSOR_WL_ADDED');
			break;
			/*
			// Coded an option for editing, but disabled it due to lack of need.
			// Words can simply be deleted and added again if there is a typo or such.
			// I'll keep this here in case it's needed later, but it has not been
			// tested much.
			case 'save':
				$edits	= utf8_normalize_nfc(request_var('whitelist_edits', array(0 => ''), true));
				$whitelist_by_ids = array_flip($censor_suite->whitelist);
				$new_words = array();
				foreach( $edits as $k => $v )
				{
					$v = htmlspecialchars(trim(strtolower($v)));
					if( empty($v) )
					{
						continue;
					}
					if( isset($whitelist_by_ids[$k]) && $v != $whitelist_by_ids[$k] )
					{
						$params = array('whitelist_word' => $v);
						$sql = 'UPDATE ' . WORDS_WHITELIST_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $params) . ' WHERE whitelist_id = ' . $k;
						$db->sql_query($sql);
						$new_words[] = $whitelist_by_ids[$k] . ' => ' . $v;
					}
				}

				// TODO: Different message if no changes were made

				$censor_suite->destroy_whitelist();
				add_log('admin', 'ACP_CENSOR_LOG_WL_EDITED', implode(', ', $new_words));
				$this->trigger_message('ACP_CENSOR_WL_EDITED');
			break;
			*/
			case 'delete':
				$delete_ids = request_var('delete_id', array(0));
				if( empty($delete_ids) )
				{
					break;
				}

				if( confirm_box(true) )
				{
					$deleted_words = array();
					$sql = 'SELECT whitelist_word FROM ' . WORDS_WHITELIST_TABLE . '
							WHERE ' . $db->sql_in_set('whitelist_id', $delete_ids);
					$result = $db->sql_query($sql);
					while( $row = $db->sql_fetchrow($result) )
					{
						$deleted_words[] = $row['whitelist_word'];
					}
					$db->sql_freeresult($result);
					$db->sql_query('DELETE FROM ' . WORDS_WHITELIST_TABLE . '
									WHERE ' . $db->sql_in_set('whitelist_id', $delete_ids));
					$censor_suite->destroy_whitelist();
					add_log('admin', 'ACP_CENSOR_LOG_WL_REMOVED', implode(', ', $deleted_words));
					$this->trigger_message('ACP_CENSOR_WL_REMOVED');
				}
				else
				{
					$params = array(
						'i'			=> $this->id,
						'mode'		=> $this->mode,
						'action'	=> $this->action,
					);
					for( $i = 0, $num = count($delete_ids) ; $i < $num ; $i++ )
					{
						$params['delete_id[' . $i . ']'] = $delete_ids[$i];
					}
					confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields($params));
				}
			break;
		}

		$sql = 'SELECT * FROM ' . WORDS_WHITELIST_TABLE . '
				ORDER BY whitelist_word ASC';
		$result = $db->sql_query($sql);
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('words', array(
				'WORD'		=> $row['whitelist_word'],
				'WORD_ID'	=> $row['whitelist_id'],
			));
		}
		$template->assign_vars(array(
			'S_WHITELIST'	=> true,
		));
	}

	function page_config()
	{
		global $template, $config, $user, $cache;

		$user->add_lang('acp/board');

		$display_vars = array(
			'title'	=> 'ACP_CENSOR_CONFIG_TITLE',
			'vars'	=> array(
				'legend1'					=> 'ACP_CENSOR_GEN_CONFIG',
				'censor_substitutes'		=> array('lang' => 'ACP_CENSOR_CFG_SUBSTITUTES',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_study'				=> array('lang' => 'ACP_CENSOR_CFG_STUDY',			'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_check_usernames'		=> array('lang' => 'ACP_CENSOR_CHECK_USERNAMES',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_replacement'		=> array('lang' => 'ACP_CENSOR_CFG_REPLACEMENT',	'validate' => 'string',	'type' => 'text:40:255', 'explain' => true),

				'legend2'					=> 'ACP_CENSOR_BLOCK',
				'censor_block'				=> array('lang' => 'ACP_CENSOR_BLOCK',			'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_whitelist'		=> array('lang' => 'ACP_CENSOR_WHITELIST_OPTION',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_logs'		=> array('lang' => 'ACP_CENSOR_BLOCK_LOGS',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_report_posts'		=> array('lang' => 'ACP_CENSOR_REPORT_POSTS',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_report_pms'			=> array('lang' => 'ACP_CENSOR_REPORT_PMS',		'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_report_reason'		=> array('lang' => 'ACP_CENSOR_REPORT_REASON',	'validate' => 'int:0',	'type' => 'select', 'explain' => true, 'method' => 'select_report'),
				'censor_block_hilight'		=> array('lang' => 'ACP_CENSOR_HILIGHT_CLASS',	'validate' => 'string',	'type' => 'text:40:255', 'explain' => true),
				'censor_block_class'		=> array('lang' => 'ACP_CENSOR_PREVIEW_CLASS',	'validate' => 'string',	'type' => 'text:40:255', 'explain' => true),
				'censor_disable_display'	=> array('lang' => 'ACP_CENSOR_DISPLAY_DISABLE','validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => true),

				/* Currently not used
				// part of possible future feature
				'legend4'					=> 'ACP_CENSOR_LINK_ASSIST',
				'censor_link_hovers'		=> array('lang' => 'ACP_CENSOR_LINK_ASSIST',	'validate' => 'bool',	'type' => 'radio:enabled_disabled', 'explain' => true),
				'censor_js_library'		=> array('lang' => 'ACP_CENSOR_JS_LIBRARY',	'validate' => 'int:0',	'type' => 'select', 'explain' => true, 'method' => 'select_library'),
				'censor_google_api_key'		=> array('lang' => 'ACP_CENSOR_GOOGLE_API',		'validate' => 'string',	'type' => 'text:40:255', 'explain' => true),
				*/

				'legend5'				=> 'ACP_SUBMIT_CHANGES',
			)
		);

		$this->new_config = $config;
		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if whished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		$submit = (isset($_POST['submit'])) ? true : false;

		if ($submit && !check_form_key($this->form_name))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}
		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				set_config($config_name, $config_value);
			}
		}

		if ($submit)
		{
			// Destroy some caches incase the Bypass Detection settings were changed.
			$cache->destroy('_word_censors');
			$cache->destroy('_disallowed_usernames');
			add_log('admin', 'ACP_CENSOR_LOG_CONFIG');
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$this->tpl_name = 'acp_censor_config';
		$this->page_title = 'ACP_CENSOR_CONFIG_TITLE';
		$backtrack_limit	= ini_get('pcre.backtrack_limit');

		$template->assign_vars(array(
			'L_TITLE'			=> $user->lang['ACP_CENSOR_CONFIG_TITLE'],
			'L_TITLE_EXPLAIN'	=> $user->lang['ACP_CENSOR_CONFIG_EXPLAIN'],
			'BACKTRACK_LIMIT'	=> ($backtrack_limit === false) ? $user->lang['ACP_CENSOR_BACKTRACK_LIMIT_NONE']: strval($backtrack_limit / 2) . ' '. $user->lang['ACP_CENSOR_CHARACTERS'],

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),

			'U_ACTION'			=> $this->u_action
		));


		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
				);

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> $content,
				)
			);

			unset($display_vars['vars'][$config_key]);
		}
	}

	function page_bypass()
	{
		global $db, $template, $user, $cache;

		$this->page_title = 'ACP_CENSOR_BYPASS';
		$this->action = (isset($_POST['add'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : $this->action);
		$s_hidden_fields = '';

		switch( $this->action )
		{
			case 'edit':
				$character_id = utf8_normalize_nfc(request_var('id', '', true));
				if ( empty($character_id) )
				{
					trigger_error($user->lang['NO_WORD'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'SELECT *
					FROM ' . WORDS_SUBSTITUTIONS_TABLE . "
					WHERE substitution_key = '" . $db->sql_escape($character_id) . "'";
				$result = $db->sql_query($sql);
				$character_info = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $character_id . '" />';
			case 'add':
				$substitutions = $character = '';
				if( !empty($character_info) )
				{
					$character = $character_info[0]['substitution_key'];
					for( $i = 0, $num = count($character_info) ; $i < $num ; $i++ )
					{
						$substitutions .= "\n" . $character_info[$i]['substitution_replace'];
					}
				}
				$template->assign_vars(array(
					'S_EDIT_BYPASS'		=> true,
					'U_ACTION'			=> $this->u_action,
					'U_BACK'			=> $this->u_action,
					'CHARACTER'			=> $character,
					'SUBSTITUTIONS'		=> trim($substitutions),
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields
				));

				return;
			break;
			case 'save':
				if (!check_form_key($this->form_name))
				{
					trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
				}

				$character_id	= utf8_normalize_nfc(request_var('id', '', true));
				$character		= utf8_normalize_nfc(request_var('character', '', true));
				$substitutions	= utf8_normalize_nfc(request_var('substitutions', '', true));

				if ($character === '' || $substitutions === '')
				{
					trigger_error($user->lang['ENTER_WORD'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$character_id	= strtolower($character_id);
				$character		= strtolower($character);
				$substitutions	= strtolower($substitutions);

				$sql = 'SELECT *
					FROM ' . WORDS_SUBSTITUTIONS_TABLE . "
					WHERE substitution_key = '" . $db->sql_escape($character_id) . "'";
				$result = $db->sql_query($sql);
				$current_substitutions = array();
				while( $row = $db->sql_fetchrow($result) )
				{
					$current_substitutions[$row['substitution_replace']] = $row['substitution_id'];
				}
				$db->sql_freeresult($result);

				$substitutions	= explode("\n", $substitutions);
				for( $i = 0, $num = count($substitutions) ; $i < $num ; $i++ )
				{
					$substitutions[$i] = trim($substitutions[$i]);
				}
				sort($substitutions);
				if( empty($character_id) )
				{
					for( $i = 0, $num = count($substitutions) ; $i < $num ; $i++ )
					{
						if( empty($substitutions[$i]) )
						{
							continue;
						}
						$sql_array = array(
							'substitution_key'		=> $character,
							'substitution_replace'	=> $substitutions[$i],
						);
						$db->sql_query('INSERT INTO ' . WORDS_SUBSTITUTIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));
					}
				}
				else
				{
					$where_sql = "WHERE substitution_key = '" . $db->sql_escape($character_id) . "'";
					if( $character_id != $character )
					{
						// Character was changed
						$db->sql_query('UPDATE ' . WORDS_SUBSTITUTIONS_TABLE . "
								SET substitution_key = '" . $db->sql_escape($character) . "'
								$where_sql");
						$where_sql = "WHERE substitution_key = '" . $db->sql_escape($character) . "'";
					}
					for( $i = 0, $num = count($substitutions) ; $i < $num ; $i++ )
					{
						if( empty($substitutions[$i]) )
						{
							continue;
						}
						if( !isset($current_substitutions[$substitutions[$i]]) )
						{
							// We're adding a new substitution
							$sql_array = array(
								'substitution_key'		=> $character,
								'substitution_replace'	=> $substitutions[$i],
							);
							$db->sql_query('INSERT INTO ' . WORDS_SUBSTITUTIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));
						}
					}
					// Now check for removed substitutions to delete from database
					$delete_ids = array();

					foreach($current_substitutions as $replacement => $sub_id )
					{
						if( !in_array($replacement, $substitutions) )
						{
							$delete_ids[] = $sub_id;
						}
					}
					if( !empty($delete_ids) )
					{
						$db->sql_query('DELETE FROM ' . WORDS_SUBSTITUTIONS_TABLE . ' WHERE ' . $db->sql_in_set('substitution_id', $delete_ids));
					}
				}

				$cache->destroy('_word_censors');
				$cache->destroy('_disallowed_usernames');
				$log_action = ($character_id) ? 'ACP_CENSOR_LOG_BYPASS_EDIT' : 'ACP_CENSOR_LOG_BYPASS_ADD';
				add_log('admin', $log_action, $character_id);

				$this->trigger_message((!empty($character_id)) ? 'ACP_CENSOR_BYPASS_UPDATED' : 'ACP_CENSOR_BYPASS_ADDED');

			break;
		}

		$template->assign_vars(array(
			'S_BYPASS_LIST'	=> true,
		));

		$sql = 'SELECT * FROM ' . WORDS_SUBSTITUTIONS_TABLE;
		$result = $db->sql_query($sql);
		$substitutions = array();
		while( $row = $db->sql_fetchrow($result) )
		{
			if( !isset($substitutions[$row['substitution_key']]) )
			{
				$substitutions[$row['substitution_key']] = array();
			}
			$substitutions[$row['substitution_key']][] = $row['substitution_replace'];
		}
		$db->sql_freeresult($result);

		ksort($substitutions);
		foreach( $substitutions as $k => $v )
		{
			$total = count($v);
			$template->assign_block_vars('words', array(
				'CHARACTER'			=> $k,
				'TOTAL'				=> $total,
				'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;id=' . htmlspecialchars($k),
			));
			for( $i = 0 ; $i < $total ; $i++ )
			{
				$template->assign_block_vars('words.subs', array(
					'SUBSTITUTION'		=> $v[$i],
				));
			}
		}
	}

	function get_lists(&$words, &$words_full, &$disallowed_names)
	{
		global $db;

		$words = $words_full = $disallowed_names = array();

		$sql = 'SELECT *
			FROM ' . WORDS_TABLE . '
			ORDER BY word';
		$result		= $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$words[]		= $row['word'];
			$words_full[]	= $row;
		}
		$db->sql_freeresult($result);

		$sql = 'SELECT *
			FROM ' . DISALLOW_TABLE;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$disallowed_names[]			= $row['disallow_username'];
		}
		$db->sql_freeresult($result);
	}

	function page_words()
	{
		global $db, $template, $phpbb_root_path, $cache, $user, $config;

		$this->action = (isset($_POST['install'])) ? 'install' : ((isset($_POST['uninstall'])) ? 'uninstall' : $this->action);
		$this->page_title = ( $config['censor_check_usernames']) ? 'ACP_CENSOR_IMPORT': 'ACP_CENSOR_IMPORT_NO_CHECK';
		$start	= request_var('start', 0);

		$word_list		= file($phpbb_root_path . 'mods/censor/wordlist.txt');
		$total_words	= count($word_list);
		for( $i = 0; $i < $total_words ; $i++ )
		{
			$word_list[$i] = htmlspecialchars(trim($word_list[$i]));
		}
		sort($word_list);

		$words = $words_full = $disallowed_names = array();
		$this->get_lists($words, $words_full, $disallowed_names);

		$action_words = $this->request_list('words', $word_list, $words);
		$action_names = $this->request_list('names', $word_list, $disallowed_names);

		switch( $this->action )
		{
			case 'install':

				// Remove any words already in the database from our lists
				$this->purge_list($action_words, $words);
				$this->purge_list($action_names, $disallowed_names);
				$register_blocked	= $this->check_registered_disallows($action_names);

				if( empty($action_words) && empty($action_names) )
				{
					break;
				}

				if( confirm_box(true) )
				{
					if( !empty($action_words) )
					{
						for( $i = 0, $num = count($action_words) ; $i < $num ; $i++ )
						{
							$params = array(
								'word'				=>	$action_words[$i],
								'replacement'		=>	$config['censor_replacement'],
							);
							$db->sql_query('INSERT INTO ' . WORDS_TABLE . ' ' . $db->sql_build_array('INSERT', $params));
						}
						$cache->destroy('_word_censors');
						add_log('admin', 'ACP_CENSOR_LOG_WORDS_INSTALLED', implode(', ', $action_words));
						$this->trigger_message('ACP_CENSOR_WORDS_INSTALLED');
					}

					if( !empty($action_names) )
					{
						$did_install	= false;
						for( $i = 0, $num = count($action_names) ; $i < $num ; $i++ )
						{
							if( isset($register_blocked[$i]) )
							{
								continue;
							}
							$params = array(
								'disallow_username'	=>	str_replace('*', '%', $action_names[$i])
							);
							$db->sql_query('INSERT INTO ' . DISALLOW_TABLE . ' ' . $db->sql_build_array('INSERT', $params));
							$did_install	= true;
						}

						if( !empty($register_blocked) )
						{
							$this->trigger_message($user->lang('ACP_CENSOR_NAMES_NOT_INSTALLED', implode(', ', $register_blocked)), '', false);
						}

						if( $did_install )
						{
							$cache->destroy('_disallowed_usernames');
							$installed_names = array_diff($action_names, $register_blocked);
							add_log('admin', 'ACP_CENSOR_LOG_NAMES_INSTALLED', implode(', ', $installed_names));
							$this->trigger_message('ACP_CENSOR_NAMES_INSTALLED');
						}
					}

					$this->get_lists($words, $words_full, $disallowed_names);
				}
				else
				{
					$this->import_confirm_box($action_words, $action_names);
				}
			break;
			case 'uninstall':
				if( empty($action_words) && empty($action_names) )
				{
					break;
				}
				if( confirm_box(true) )
				{
					if( !empty($action_words) )
					{
						for( $i = 0, $num = count($action_words) ; $i < $num ; $i++ )
						{
							$db->sql_query('DELETE FROM ' . WORDS_TABLE . " WHERE word = '" . $action_words[$i] . "'");
						}
						add_log('admin', 'ACP_CENSOR_LOG_WORDS_REMOVED', implode(', ', $action_words));
						$this->trigger_message('ACP_CENSOR_WORDS_REMOVED');
					}

					if( !empty($action_names) )
					{
						for( $i = 0, $num = count($action_names) ; $i < $num ; $i++ )
						{
							$db->sql_query('DELETE FROM ' . DISALLOW_TABLE . " WHERE disallow_username = '" . str_replace('*', '%', $action_names[$i]) . "'");
						}
						add_log('admin', 'ACP_CENSOR_LOG_NAMES_REMOVED', implode(', ', $action_names));
						$this->trigger_message('ACP_CENSOR_NAMES_REMOVED');
					}

					$this->get_lists($words, $words_full, $disallowed_names);
				}
				else
				{
					$this->import_confirm_box($action_words, $action_names);
				}
			break;
		}

		$template->assign_var('PAGINATION', generate_pagination($this->u_action, $total_words, $this->per_page, $start, true));
		$display_num = ( $start + $this->per_page < $total_words ) ? $start + $this->per_page: $total_words;
		$register_blocked = $this->check_registered_disallows($word_list, $start, $display_num);
		for( $i = $start; $i < $display_num ; $i++ )
		{
			$word			= $word_list[$i];
			$words_key		= array_search($word, $words);
			$disallow_key	= array_search(str_replace('*', '%', $word), $disallowed_names);
			$replacement	= '';
			$s_in_words		= false;
			$s_in_disallow	= false;
			$s_registered	= false;
			if( $words_key !== false )
			{
				$s_in_words		= true;
				$replacement	= $words_full[$words_key]['replacement'];
			}
			if( $disallow_key !== false )
			{
				$s_in_disallow	= true;
			}
			elseif( isset($register_blocked[$i]) )
			{
				$s_registered	= true;
			}
			$template->assign_block_vars('words', array(
				'WORD'				=> $word,
				'REPLACEMENT'		=> $replacement,
				'S_IN_WORDS'		=> $s_in_words,
				'S_IN_DISALLOW'		=> $s_in_disallow,
				'S_REGISTERED'		=> $s_registered,
				'ID'				=> $i+1,
			));
		}
	}

	// Check disallowed names against already registered usernames
	function check_registered_disallows($list, $start = 0, $length = false)
	{
		global $config, $db;

		if( !$config['censor_check_usernames'] )
		{
			return array();
		}

		if( empty($list) )
		{
			return array();
		}

		$register_blocked	= array();
		$where_sql			= array();
		$select_sql			= array();
		$length = ( $length === false ) ? count($list) : $length;
		for( $i = $start; $i < $length ; $i++ )
		{
			if( empty($list[$i]) )
			{
				continue;
			}
			$like_expression	= $db->sql_like_expression(str_replace('*', $db->any_char, $db->sql_escape($list[$i])));
			$where_sql[]		= 'username_clean ' . $like_expression;
			$select_sql[]		= 'username_clean ' . $like_expression . ' AS condition' . $i;
		}

		if( !empty($where_sql) )
		{
			$sql	= 'SELECT ' . implode(', ', $select_sql) . '
						FROM ' . USERS_TABLE . '
						WHERE ' . implode(' OR ', $where_sql);
			$result	= $db->sql_query($sql);
			while( $row = $db->sql_fetchrow($result) )
			{
				foreach( $row as $k => $v )
				{
					if( !empty($v) )
					{
						$item	= intval(str_replace('condition', '', $k));
						$register_blocked[$item] = $list[$item];
					}
				}
			}
			$db->sql_freeresult($result);
		}

		return $register_blocked;
	}

	function import_confirm_box($action_words, $action_names)
	{
		global $user;
		$params = array(
			'i'			=> $this->id,
			'mode'		=> $this->mode,
			'action'	=> $this->action,
			'words'		=> $action_words,
			'names'		=> $action_names,
		);
		confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields($params));
	}

	function request_list($var, $word_list, $installed_list)
	{
		$list = utf8_normalize_nfc(request_var($var, array(''), true));

		if( empty($list) )
		{
			return false;
		}

		// Make sure the passed words are in our list
		$list = array_intersect($list, $word_list);
		if( empty($list) )
		{
			return false; // No valid words from our list
		}
		sort($list);

		return $list;
	}

	function purge_list(&$list, $installed_list)
	{
		if( empty($list) )
		{
			return false;
		}
		$list = array_diff($list, $installed_list);
		sort($list);
	}

	function trigger_message($message, $title = '', $success = true)
	{
		global $user, $template;

		$template->assign_block_vars('message', array(
			'MESSAGE_TITLE'	=>	$title,
			'MESSAGE_TEXT'	=>	( isset($user->lang[$message]) ) ? $user->lang[$message] : $message,
			'S_USER_NOTICE'	=>	( $success ) ? true: false,
		));
	}

	function select_report($value, $key = '')
	{
		global $db, $user;

		$user->add_lang('mcp');

		$options = '';
		$sql = 'SELECT *
			FROM ' . REPORTS_REASONS_TABLE . '
			ORDER BY reason_order ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			// Localize reason title
			if (isset($user->lang['report_reasons']['TITLE'][strtoupper($row['reason_title'])]))
			{
				$row['reason_title'] = $user->lang['report_reasons']['TITLE'][strtoupper($row['reason_title'])];
			}
			$options .= '<option value="' . $row['reason_id'] . '"' . (($value == $row['reason_id']) ? ' selected="selected"' : '') . '>' . $row['reason_title'] . '</option>';
		}
		$db->sql_freeresult($result);

		return $options;
	}

/*	Currently unused - part of Link Assistant feature
	function select_library($value, $key = '')
	{
		global $user;

		$list = array(
			'CENSOR_MOOTOOLS_LOCAL'		=>	CENSOR_MOOTOOLS_LOCAL,
			'CENSOR_JQUERY_LOCAL'		=>	CENSOR_JQUERY_LOCAL,
			'CENSOR_MOOTOOLS_REMOTE'	=>	CENSOR_MOOTOOLS_REMOTE,
			'CENSOR_JQUERY_REMOTE'		=>	CENSOR_JQUERY_REMOTE,
		);
		$options = '';
		foreach( $list as $k => $v )
		{
			$options .= '<option value="' . $v . '"' . (($value == $v) ? ' selected="selected"' : '') . '>' . $user->lang[$k] . '</option>';
		}

		return $options;
	}
*/
}

?>