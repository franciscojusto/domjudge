<?php
/**
*
* @package acp
* @version $Id$
* @copyright (c) 2011 Thoul
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
class acp_censor_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_censor',
			'title'		=> 'ACP_CENSOR',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'config'		=> array('title' => 'ACP_CENSOR_CONFIG', 'auth' => 'acl_a_words && acl_a_names', 'cat' => array('ACP_CENSOR')),
				'words'			=> array('title' => 'ACP_CENSOR_IMPORT', 'auth' => 'acl_a_words && acl_a_names', 'cat' => array('ACP_CENSOR')),
				'bypass'		=> array('title' => 'ACP_CENSOR_BYPASS', 'auth' => 'acl_a_words && acl_a_names', 'cat' => array('ACP_CENSOR')),
				'white'		=> array('title' => 'ACP_CENSOR_WHITELIST', 'auth' => 'acl_a_words', 'cat' => array('ACP_CENSOR')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>