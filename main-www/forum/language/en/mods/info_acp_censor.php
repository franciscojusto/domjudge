<?php
/**
*
* Censor Suite [English]
*
* @package language
* @version $Id$
* @copyright (c) November 30, 2011 Thoul
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/**
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_CENSOR'						=> 'Censor Suite',
	'ACP_CENSOR_MAP_LOAD_FAIL'			=> 'Failed to load Replacement Character Map',
	'ACP_CENSOR_INSTALLED'				=> 'Installed',
	'ACP_CENSOR_INSTALL'				=> 'Install',
	'ACP_CENSORS'						=> 'Censors',
	'ACP_CENSOR_MARK_ALL_CENSORS'		=> 'Mark All Censors',
	'ACP_CENSOR_UNMARK_ALL_CENSORS'		=> 'Unmark All Censors',
	'ACP_CENSOR_MARK_ALL_NAMES'			=> 'Mark All Usernames',
	'ACP_CENSOR_UNMARK_ALL_NAMES'		=> 'Unmark All Usernames',
	'ACP_CENSOR_INSTALL_MARKED'			=> 'Install Marked',
	'ACP_CENSOR_UNINSTALL_MARKED'		=> 'Uninstall Marked',
	'ACP_CENSOR_BYPASS'					=> 'Bypass Detection',
	'ACP_CENSOR_IMPORT'					=> 'Import Word List',
	'ACP_CENSOR_IMPORT_NO_CHECK'		=> 'Import Word List',
	'ACP_CENSOR_IMPORT_EXPLAIN'			=> 'From this panel, you can import entries to your censored words and disallowed usernames. Select words from Censor Suite\'s default word list for importing or removal below. Wildcards (*) are used in the word fields, e.g. *test* will match detestable, test* would match testing, *test would match detest.<br /><br /><strong>Please note that any entries matching previously registered usernames cannot be installed as disallowed usernames. To disallow a username that has already been registered, you must first delete or rename all users with that username.</strong>',
	'ACP_CENSOR_IMPORT_NO_CHECK_EXPLAIN'			=> 'From this panel, you can import entries to your censored words and disallowed usernames. Select words from Censor Suite\'s default word list for importing or removal below. Wildcards (*) are used in the word fields, e.g. *test* will match detestable, test* would match testing, *test would match detest.',
	'ACP_CENSOR_CHECK_USERNAMES'		=> 'Check Username Status',
	'ACP_CENSOR_CHECK_USERNAMES_EXPLAIN'	=> 'When importing filters to as Disallowed Usernames, the new filters can be checked against currently registered names. If a match is found, the matching filter will not be imported.',
	'ACP_CENSOR_WORDS_INSTALLED'		=> 'The selected words have been installed.',
	'ACP_CENSOR_WORDS_REMOVED'			=> 'The selected words have been removed.',
	'ACP_CENSOR_BYPASS_EXPLAIN'			=> 'This panel allows you to define character substitutions for use in detecting attempts to bypass detection of censored words and disallowed usernames by replacing some characters, such as changing "test" to "tezt." Each character in censored words and disallowed usernames will be compared to all of the substitutions defined for that character here.',
	'ACP_CENSOR_CHARACTER'				=> 'Character',
	'ACP_CENSOR_SUBSTITUTIONS'			=> 'Substitutions',
	'ACP_CENSOR_ADD_CHARACTER'			=> 'Add Character',
	'ACP_CENSOR_EDIT_SUBSTITUTIONS'		=> 'Edit Substitutions',
	'ACP_CENSOR_BYPASS_UPDATED'			=> 'The bypass character substitutions have been updated.',
	'ACP_CENSOR_BYPASS_ADDED'			=> 'The bypass character substitutions have been added.',
	'ACP_CENSOR_CHARACTER_EXPLAIN'		=> 'Enter a character or combination of characters that should be checked for the substitutions defined below.',
	'ACP_CENSOR_SUBSTITUTIONS_EXPLAIN'	=> 'Enter any characters or character combinations that should be considered as substitutions for the character entered above. Place each new character or combination on a new line. Substitutions are case insensitive and will be converted to lowercase. Wildcards are not supported; entering an asterisk (*) will treat it as a literal character.',
	'ACP_CENSOR_CFG_SUBSTITUTES'		=> 'Bypass Detection',
	'ACP_CENSOR_CFG_REPLACEMENT'		=> 'Default censor replacement',
	'ACP_CENSOR_CFG_REPLACEMENT_EXPLAIN'	=> 'This value will be used as the replacement text for word censors added using the Import Word List area. Replacement text can be individually changed in the default word censor controls.',
	'ACP_CENSOR_CONFIG'					=> 'Configuration',
	'ACP_CENSOR_GEN_CONFIG'				=> 'General Configuration',
	'ACP_CENSOR_CONFIG_TITLE'			=> 'Censor Suite Configuration',
	'ACP_CENSOR_CONFIG_EXPLAIN'			=> 'In this area, you are able to alter configuration settings related to the features of Censor Suite.',
	'ACP_CENSOR_LOG_CONFIG'				=> '<strong>Censor Suite Configuration Updated</strong>',
	'ACP_CENSOR_LOG_BYPASS_EDIT'		=> '<strong>Censor Suite Bypass Definition Edited</strong><br />» %s',
	'ACP_CENSOR_LOG_BYPASS_ADD'			=> '<strong>Censor Suite Bypass Definition Added</strong><br />» %s',
	'ACP_CENSOR_LOG_WORDS_INSTALLED'	=> '<strong>Censor Suite: Words Imported</strong><br />» %s',
	'ACP_CENSOR_LOG_WORDS_REMOVED'		=> '<strong>Censor Suite: Words Uninstalled</strong><br />» %s',
	'ACP_CENSOR_LOG_NAMES_INSTALLED'	=> '<strong>Censor Suite: Usernames Imported</strong><br />» %s',
	'ACP_CENSOR_LOG_NAMES_REMOVED'		=> '<strong>Censor Suite: Usernames Uninstalled</strong><br />» %s',
	'ACP_CENSOR_LOG_WL_ADDED'			=> '<strong>Censor Suite: Whitelist Words Added</strong><br />» %s',
	'ACP_CENSOR_LOG_WL_EDITED'			=> '<strong>Censor Suite: Whitelist Words Edited</strong><br />» %s',
	'ACP_CENSOR_LOG_WL_REMOVED'			=> '<strong>Censor Suite: Whitelist Words Removed</strong><br />» %s',
	'ACP_CENSOR_WL_ADDED'				=> 'New words have been added to the whitelist.',
	'ACP_CENSOR_WL_REMOVED'				=> 'The selected words have been removed from the whitelist.',
	'ACP_CENSOR_TOTAL'					=> 'Total',
	'ACP_CENSOR_CFG_SUBSTITUTES_EXPLAIN'	=> 'Bypass detection can catch variants of censored words and disallowed usernames that might normally allow a person to evade typical censor checks.',
	'ACP_CENSOR_CFG_STUDY'				=> 'Expression Studying',
	'ACP_CENSOR_CFG_STUDY_EXPLAIN'		=> 'Studying of regular expressions can increase performance of word censors. Enabling this is recommended when using Bypass Detection.',
	'ACP_CENSOR_BLOCK'					=> 'Censor Block',
	'ACP_CENSOR_REPORT_POSTS'			=> 'Automatic Post Reporting',
	'ACP_CENSOR_REPORT_REASON'			=> 'Report Reason',
	'ACP_CENSOR_HILIGHT_CLASS'			=> 'CSS Highlight Class',
	'ACP_CENSOR_HILIGHT_CLASS_EXPLAIN'	=> 'In notices regarding blocked submissions, censor triggering content will be wrapped in span tags using the CSS class name entered here. You can use this class to emphasize the triggering content.',
	'ACP_CENSOR_PREVIEW_CLASS'			=> 'CSS Preview Class',
	'ACP_CENSOR_PREVIEW_CLASS_EXPLAIN'	=> 'In notices regarding blocked submissions, the entire content of the form field will be wrapped in span tags using the CSS class name entered here. You can use this class to reduce emphasis on non-triggering portions of the content.',
	'ACP_CENSOR_REPORT_REASON_EXPLAIN'	=> 'This reason will be used for automatic reports. Reasons can be edited ',
	'ACP_CENSOR_BLOCK_EXPLAIN'			=> 'Posts and text profile fields will be checked against word censors at submission time. If censored content is found, an error message will instruct the user to alter the submission. Always disabled on Administration Control Panel pages.',
	'ACP_CENSOR_REPORT_POSTS_EXPLAIN'	=> 'Posts that trigger Censor Block will automatically be reported for later review, allowing you to monitor posts for possible censor filter bypasses.',
	'ACP_CENSOR_REPORT_PMS'				=> 'Automatic Private Message Reporting',
	'ACP_CENSOR_REPORT_PMS_EXPLAIN'		=> 'PMs that trigger Censor Block will automatically be reported for later review, allowing you to monitor messages for possible censor filter bypasses.',
	'ACP_CENSOR_BLOCK_LOGS'				=> 'Log triggering words',
	'ACP_CENSOR_BLOCK_LOGS_EXPLAIN'		=> 'Creates log entries containing content that triggers Censor Block.',
	'ACP_CENSOR_DISPLAY_DISABLE'		=> 'Disable display censoring',
	'ACP_CENSOR_DISPLAY_DISABLE_EXPLAIN'	=> 'Most user submitted text is checked with censors when displayed. Disabling this can (but is not guaranteed to) increase performance. This is not recommended if any censorable content may have been entered in areas not protected by Censor Block.',
	'ACP_CENSOR_LINK_ASSIST'			=> 'Link Assistant',
	'ACP_CENSOR_GOOGLE_API'				=> 'Google API Key',
	'ACP_CENSOR_GOOGLE_API_EXPLAIN'		=> 'When using a library hosted by Google, an API key may be used. API keys are optional and may be <a href="http://code.google.com/apis/loader/signup.html">obtained from Google</a>.',
	'ACP_CENSOR_JS_LIBRARY'				=> 'Javascript Library',
	'ACP_CENSOR_JS_LIBRARY_EXPLAIN'		=> 'Select a third party library used to display controls. If you have not independently installed one on your site or your forum style did not include one, you may select a library hosted remotely by Google. ',
	'ACP_CENSOR_LINK_ASSIST_EXPLAIN'	=> 'The feature will allow admins with word censor editing permissions to quickly add links to the censors.',
	'ACP_CENSOR_NAMES_NOT_INSTALLED'	=> 'The following entries could not be installed as disallowed usernames. They match usernames that have been previously registered:<br />%s',
	'ACP_CENSOR_NAMES_INSTALLED'		=> 'The selected disallowed usernames have been installed.',
	'ACP_CENSOR_NAMES_REMOVED'			=> 'The selected disallowed usernames have been removed.',
	'ACP_CENSOR_NAME_REGISTERED'		=> 'Registered',
	'ACP_CENSOR_NAME_REGISTERED_EXPLAIN'	=> 'This entry matches a currently registered username.',
	'ACP_CENSOR_WHITELIST'				=> 'Censor Whitelist',
	'ACP_CENSOR_WHITELIST_EXPLAIN'		=> 'The Whitelist feature allows you to define words that should not be prevented from entering your database by the Censor Block feature, even if they match an existing word censor filter. For example, if "test*" is defined as a censor and "tester" is added to the whitelist, users will be allowed to post "tester" but not "testing" or "tested."',
	'ACP_CENSOR_WHITELIST_OPTION'		=> 'Use Whitelist',
	'ACP_CENSOR_WHITELIST_OPTION_EXPLAIN'	=> 'Allows safe words and phrases to be excluded from being blocked. The whitelist can be used only with blocking at submission time.',
	'ADD_WORDS'							=> 'Add New Words',
	'ACP_CENSOR_ADD_WORDS_EXPLAIN'		=> 'Enter new words to add to the Whitelist. Place each word on a new line. The Whitelist is case insensitive. Wildcards are not permitted.',
	'ACP_CENSOR_EDIT_CHANGED'			=> 'Submit Changes',
	'ACP_CENSOR_STATUS'					=> 'Other Status',
	'ACP_CENSOR_BACKTRACK_LIMIT'		=> 'Backtrack Limit',
	'ACP_CENSOR_BACKTRACK_LIMIT_EXPLAIN'	=> 'PHP enforces a maximum length of messages checked against the Censor Whitelist; messages longer than this will not use the Whitelist. To change this limit, the pcre.backtrack_limit setting of PHP must be altered. To allow for multibyte characters, that setting should be at least double of the desired limit.',
	'ACP_CENSOR_BACKTRACK_LIMIT_NONE'	=> "This server's version of PHP does not enforce a limit.",
	'ACP_CENSOR_CHARACTERS'				=> 'Characters',
	'UNKNOWN_MODE'						=> 'The mode does not exist.',
	'OUT_OF_RANGE'						=> 'Range mismatch: Something went wrong with the submission process. Please go back and try again.',
	'ACP_CENSOR_WL_NO_WORDS_SUBMIT'		=> 'No valid new words were submitted.',
	'CENSOR_MOOTOOLS_LOCAL'				=> 'Mootools (local)',
	'CENSOR_JQUERY_LOCAL'				=> 'jQuery (local)',
	'CENSOR_MOOTOOLS_REMOTE'			=> 'Mootools (Google Hosted)',
	'CENSOR_JQUERY_REMOTE'				=> 'jQuery (Google Hosted)',

	// UMIL Installer
	'INSTALL_CENSORSUITE'					=> 'Install Censor Suite?',
	'INSTALL_CENSORSUITE_CONFIRM'			=> 'Are you ready to install Censor Suite?',
	'CENSORSUITE'							=> 'Censor Suite',
	'CENSORSUITE_EXPLAIN'					=> 'Enhances word filters with new features including list importing, whitelist, bypass detection, and prevention of blocked words from entering your database..',
	'UNINSTALL_CENSORSUITE'					=> 'Uninstall Censor Suite?',
	'UNINSTALL_CENSORSUITE_CONFIRM'			=> 'Are you ready to uninstall Censor Suite?',
	'UPDATE_CENSORSUITE'					=> 'Update Censor Suite?',
	'UPDATE_CENSORSUITE_CONFIRM'			=> 'Are you ready to update Censor Suite?',

	'CENSOR_BLOCK_LOG'						=> '<strong>Triggered Censor Block</strong><br />» %s',


	'CENSOR_REPORT' => 'This message was automatically flagged by Censor Suite. During the submission process, the user entered content that triggered the word censor filters. You may wish to review this posting for attempts to bypass those filters as a means of posting inappropriate content.',
	'CENSOR_BLOCK_ERROR_DESC' => 'Prohibited content has been found in your submission. Please review and adjust the highlighted content to comply with the policies of this site. Your submission will not be saved while this content remains in the submission. If you require assistance or feel that a submission may have been flagged in error, please <a href="mailto:%1$s">contact the Board Administrator</a>. Thank you.',
	'CENSOR_BLOCK_ERROR_FIELD'	=> 'The “%1$s” must be adjusted:<br />%2$s',

));

?>