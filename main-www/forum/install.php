<?php
/*-----------------------------------------------------------------------------
    Modification Installer for phpBB 3
  ----------------------------------------------------------------------------
    install.php
       SQL Installer Script
    Generation Date: July 02, 2007  
  ----------------------------------------------------------------------------
	This file is released under the GNU General Public License Version 2.
-----------------------------------------------------------------------------*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.'.$phpEx);

// Session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if ($user->data['user_id'] == ANONYMOUS)
{
	login_box("install.$phpEx", $user->lang['LOGIN_ADMIN'], $user->lang['LOGIN_ADMIN_SUCCESS']);
}

if (!$auth->acl_get('a_'))
{
	trigger_error($user->lang['NO_ADMIN']);
}

$mod = array(
	'name'			=>	'Word Censor List',
	'version'		=>	'1.2.0',
	'copy_year'		=>	'2007',
	'author'		=>	'Thoul',
	'website'		=>	'http://www.phpbbsmith.com',
	'sitename'		=>	'phpBB Smith',
	'prev_version'	=>	'',
);

$install = $uninstall = $upgrade = FALSE;
$sql = $module_data = array();
switch( $dbms )
{
	default:
		$sql["install"] = array(
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('@$$*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('a$$*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('as$*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('a\$s*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('@\$s*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('@s$*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('amcik', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('andskota', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('arschloch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('arse*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('ass', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('assho*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('assram*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('ayir', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('bi+ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b!+ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b!tch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b!7ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('bi7ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b17ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b1+ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b1tch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('bitch*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('bastard', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('boiolas', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('bollock*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('breasts', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('buceta', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('butt-pirate', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cock*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('c0ck', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cabron', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cawk', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cazzo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('chink', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('chraa', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('chuj', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cipa', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('clits', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cum', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('cunt*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('*damn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('*d4mn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dago', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('daygo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dego', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dick*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dike*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dildo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('*dyke*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dirsa', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dupa', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dziwka', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('ejac*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Ekrem*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Ekto', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('enculer', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('faen', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fag*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fanculo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fanny', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fatass', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fat@$$', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fata$$', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fatas$', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fata\$s', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fat@\$s', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fat@s$', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fatarse', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fcuk', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('feces', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('feg', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Felcher', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('ficken', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fitt*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Flikker', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('foreskin', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Fotze', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Fu(*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('*fuck*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fuk*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('futkretzn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('fux0r', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('frig', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('frigin*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('friggin*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('gay', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('gaydar', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('gook', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('guiena', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('h0r', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('hax0r', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('h4xor', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('h4x0r', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('hell', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('helvete', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('hoer*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('honkey', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('hore', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Huevon', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('hui', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('injun', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('jackass', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('jism', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('jizz', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kanker*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kawk', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kike', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('klootzak', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('knulle', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kuk', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kuksuger', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('Kurac', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kurwa', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kusi*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('kyrpa*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('l3i+ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('l3itch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('l3i7ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('l3!tch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('l3!+ch', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('lesbian', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('lesbo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('mamhoon', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('masturbat*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('merd*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('mibun', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('monkleigh', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('motherfuck*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('mofo', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('mouliewop', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('muie', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('mulkku', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('muschi', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('nazi*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('nepesaurio', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('nigga*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('nigger*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('nutsack', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('orospu', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('paska*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('penis', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('perse', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('phuck', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('picka', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pierdol*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pillu*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pimmel', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pimpis', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('piss*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pizda', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('poontsee', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('poop', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('porn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('p0rn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pr0n', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('preteen', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('prick', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pula', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pule', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pusse', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('pussy', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('puta', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('puto', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('qahbeh', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('queef*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('queer*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('qweef', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('rautenberg', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('schaffer', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('scheiss*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('schlampe', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('schmuck', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('screw', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('scrotum', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('*shit*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('sh!t*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('sharmuta', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('sharmute', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('shemale', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('shipal', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('shiz', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('skribz', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('skurwysyn', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('slut', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('smut', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('sphencter', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('spic', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('spierdalaj', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('splooge', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('suka', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('teets', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('b00b*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('teez', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('testicle*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('titt*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('tits', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('twat*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('vagina', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('viag*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('v1ag*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('v14g*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('vi4g*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('vittu', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('w00se', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('wank*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('wetback*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('whoar', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('whore', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('wichser', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('wop*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('wtf', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('yed', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('jerk*', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('dipwad', '*')",
	"INSERT INTO " . $table_prefix . "words (word, replacement) VALUES ('zabourah', '*')");
		$sql["uninstall"] = array(
	"DELETE FROM " . $table_prefix . "words WHERE word = '@$$*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'a$$*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'as$*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'a\$s*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '@\$s*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '@s$*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'amcik'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'andskota'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'arschloch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'arse*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'ass'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'assho*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'assram*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'ayir'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'bi+ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b!+ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b!tch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b!7ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'bi7ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b17ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b1+ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b1tch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'bitch*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'bastard'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'boiolas'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'bollock*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'breasts'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'buceta'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'butt-pirate'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cock*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'c0ck'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cabron'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cawk'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cazzo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'chink'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'chraa'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'chuj'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cipa'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'clits'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cum'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'cunt*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '*damn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '*d4mn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dago'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'daygo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dego'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dick*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dike*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dildo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dipwad'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '*dyke*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dirsa'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dupa'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'dziwka'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'ejac*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Ekrem*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Ekto'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'enculer'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'faen'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fag*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fanculo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fanny'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fatass'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fat@$$'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fata$$'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fatas$'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fata\$s'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fat@\$s'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fat@s$'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fatarse'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fcuk'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'feces'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'feg'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Felcher'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'ficken'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fitt*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Flikker'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'foreskin'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Fotze'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Fu(*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '*fuck*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fuk*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'futkretzn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'fux0r'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'frig'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'frigin*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'friggin*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'gay'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'gaydar'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'gook'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'guiena'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'h0r'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'hax0r'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'h4xor'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'h4x0r'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'hell'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'helvete'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'hoer*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'honkey'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'hore'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Huevon'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'hui'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'injun'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'jackass'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'jerk*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'jism'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'jizz'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kanker*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kawk'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kike'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'klootzak'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'knulle'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kuk'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kuksuger'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'Kurac'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kurwa'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kusi*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'kyrpa*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'l3i+ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'l3itch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'l3i7ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'l3!tch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'l3!+ch'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'lesbian'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'lesbo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'mamhoon'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'masturbat*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'merd*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'mibun'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'monkleigh'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'motherfuck*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'mofo'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'mouliewop'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'muie'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'mulkku'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'muschi'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'nazi*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'nepesaurio'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'nigga*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'nigger*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'nutsack'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'orospu'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'paska*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'penis'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'perse'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'phuck'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'picka'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pierdol*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pillu*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pimmel'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pimpis'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'piss*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pizda'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'poontsee'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'poop'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'porn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'p0rn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pr0n'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'preteen'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'prick'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pula'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pule'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pusse'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'pussy'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'puta'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'puto'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'qahbeh'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'queef*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'queer*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'qweef'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'rautenberg'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'schaffer'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'scheiss*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'schlampe'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'schmuck'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'screw'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'scrotum'",
	"DELETE FROM " . $table_prefix . "words WHERE word = '*shit*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'sh!t*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'sharmuta'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'sharmute'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'shemale'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'shipal'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'shiz'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'skribz'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'skurwysyn'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'slut'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'smut'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'sphencter'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'spic'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'spierdalaj'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'splooge'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'suka'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'teets'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'b00b*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'teez'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'testicle*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'titt*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'tits'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'twat*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'vagina'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'viag*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'v1ag*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'v14g*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'vi4g*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'vittu'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'w00se'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'wank*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'wetback*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'whoar'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'whore'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'wichser'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'wop*'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'wtf'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'yed'",
	"DELETE FROM " . $table_prefix . "words WHERE word = 'zabourah'");
		
	break;
}






if( !empty($sql['install']) || !empty($module_data['install']) )
{
	$install = TRUE;
}

if( !empty($sql['uninstall']) || !empty($module_data['uninstall']) )
{
	$uninstall = TRUE;
}

if( !empty($sql['upgrade']) || !empty($module_data['upgrade']) )
{
	$upgrade = TRUE;
}

require_once($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);
$acp_modules	= new acp_modules();

$page_title = $mod['name'] . ' Installer';
$action = request_var('action', '');

$version_string = $of_name = $for_name = '';
if( !empty($mod['name']) )
{
	$of_name  = ' of ' . $mod['name'];
	$for_name = ' for ' . $mod['name'];
}

if( !empty($mod['prev_version']) && !empty($mod['version']) )
{
	$version_string = " from {$mod['name']} {$mod['prev_version']} to {$mod['name']} {$mod['version']}";
}

$page_head = <<<EOH
<html>
<head>
<title>$page_title</title>
<style type="text/css">
<!--
body
{
	color: black;
	background-color: blanchedalmond;
	margin: 0;
	padding: 0;
	font-size: 15px;
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
}

a
{
	background-color: inherit;
	text-decoration: none;
	font-size: 1.0em;
}

a:hover
{
	color: green;
	background-color: inherit;
	text-decoration: underline;
}

div#header
{
	width: 100%;
	color: saddlebrown;
	background-position: right bottom;
	border: none;
	margin-top: 0.4em;
	margin-bottom: 0.5em;
}

#logo
{
	font-size: 1.8em;
	font-weight: bold;
	padding-left: 0.5em;
}

#logo a, #logo a:hover
{
	color: saddlebrown;
	font-size: 1em;
}

#footer
{
	clear: both;
	border: none;
	border-top: 1px solid saddlebrown;
	margin: 0;
	padding: 0.3em;
	font-size: 0.7em;
	text-align: right;
}

#content
{
	background: ivory;
	border-top: 1px solid saddlebrown;
	margin-top: 0;
	padding: 0.5em 1em 0.1em;
	text-align: justify;
}

p, ul
{
	font-size: 0.8em;
}

.error
{
	font-weight: bold;
	color: red;
}

.success
{
	font-weight: bold;
	color: green;
}
-->
</style>
</head>
<body>
<div id="header">
	<div id="logo">$page_title</div>
</div>
<div id="content">
<p>
	Welcome to the {$page_title}. This is a script that you can use to install, uninstall, or upgrade the database changes required for the {$mod['name']} modification to operate correctly on your phpBB 3 forum. Please select an option below to continue.
</p>
<p>
	Please remember that this script only works with the database changes required $for_name on phpBB 3. Any file alterations or additions must be installed, uninstalled, or upgraded separately. Check the documentation $of_name for details on such steps.
</p>
EOH;

$page_tail = <<<EOH
	</div>
	<div id="footer">
		{$mod['name']} Copyright &copy; {$mod['copy_year']} by <a href="{$mod['website']}" title="Visit this web site for support">{$mod['author']}</a>
	</div>
</body>
</html>
EOH;

$url_append = $phpEx . '?sid=' . $user->data['session_id'];
$page_postaction = <<<EOH
	</p>

	<p>You should now delete install.php from your forum.</p>
	
	<p class="alert">Be sure to visit the <a href="adm/index.$url_append">Administration Control Panel</a> to check and update any configuration options that may have been affected by this process.</p>
EOH;

$results = array();
$db_errors = FALSE;
$db->sql_return_on_error(true);
$page_text = '';
if( empty($action) || !in_array($action, array('install', 'upgrade', 'uninstall')) )
{
	$page_text = <<<EOH
	<p class="alert">Please note that before proceeding, you should have or create a current full backup of your database. A backup can be used to restore your forum to a state prior to the results of any actions taken by this installer, if necessary.</p>
	<p>
EOH;
	if( $install )
	{
		$page_text .= <<<EOH
	<ul>
		<li><a href="install.$url_append&amp;action=install">Install Database Changes</a></li>
EOH;
	}
	if( $uninstall )
	{
		$page_text .= <<<EOH
		<li><a href="install.$url_append&amp;action=uninstall">Uninstall Database Changes</a></li>
EOH;
	}
	if( $upgrade )
	{
		$page_text .= <<<EOH
		<li><a href="install.$url_append&amp;action=upgrade">Upgrade Database Changes $version_string</a></li>
EOH;
	}
	$page_text .= <<<EOH
	</ul>
	</p>
EOH;
}
else
{
	run_installer();
}

function run_installer()
{
	global $action, $page_text, $for_name, $results, $install, $uninstall, $upgrade;

	if( !$$action )
	{
		$page_text .= '<p>';
		switch( $action )
		{
			case 'install':
				$page_text .= 'Installation';
			break;
			case 'uninstall':
				$page_text .= 'Uninstallation';
			break;
			case 'upgrade':
				$page_text .= 'Upgrading';
			break;
		}
		$page_text .= ' is not supported for ' . $for_name . '.</p>';
	}
	else
	{
		$page_text = "
			<p>This installer will now attempt to make the database changes{$for_name}.</p>";
		process_sql();
		process_modules();

		if( empty($results) )
		{
			$results[] = '<li>No changes were attempted! You may have already run the installer successfully.</li>';
		}
		$page_text .= '<ul>' . implode("\n", $results) . '</ul>
		<p>
			The installer process is now complete.';
	}
}

function process_modules()
{
	global $action, $module_data;
	if( empty($module_data[$action]) )
	{
		return;
	}
	switch( $action )
	{
		case 'install':
			add_modules($module_data['install']);
		break;
		case 'upgrade':
			remove_modules($module_data['upgrade']['remove']);
			add_modules($module_data['upgrade']['add']);
		break;
		case 'uninstall':
			remove_modules($module_data['uninstall']);
		break;
	}
}

function get_cat_ids($details, $module_class)
{
	global $db;

	$parents = array();
	if( !isset($details['cat']) )
	{
		return array(0);
	}
	$cats = $details['cat'];
	$sql = 'SELECT module_id FROM ' . MODULES_TABLE . '
			WHERE ' . $db->sql_in_set('module_langname', $cats) . "
				AND module_class = '" . $db->sql_escape($module_class) . "'";
	$result = $db->sql_query($sql);
	while( $row = $db->sql_fetchrow($result) )
	{
		$parents[] = $row['module_id'];
	}
	$db->sql_freeresult($result);

	if( empty($parents) )
	{
		$parents = array(0);
	}

	return $parents;
}

function remove_modules($modules)
{
	global $db, $phpbb_root_path, $phpEx, $acp_modules;

	if( empty($modules) )
	{
		return;
	}
	foreach($modules as $k=>$v)
	{
		// Check if module name and mode exist...
		$module_basename	= $v['basename'];
		$module_class		= $v['class'];
		if( !check_for_info($module_class, $module_basename) )
		{
			continue;
		}
		$fileinfo = $acp_modules->get_module_infos($module_basename, $module_class);
		$fileinfo = $fileinfo[$module_basename];

		if( !empty($fileinfo['modes']) )
		{
			uninstall_modules($fileinfo['modes'], $module_class, $module_basename);
		}

		if( isset($fileinfo['new_parents']) )
		{
			uninstall_modules($fileinfo['new_parents'], $module_class, '', true);
		}
	}
}

function uninstall_modules($modules, $module_class, $module_basename, $cats_only = FALSE)
{
	global $acp_modules, $db, $user;
	$modules = array_reverse($modules);
	foreach($modules as $module_mode => $mode_details)
	{
		// We need to get the module_id for each mode.
		$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
				WHERE module_langname = '" . $db->sql_escape($mode_details['title']) . "'
					AND module_class = '" . $db->sql_escape($module_class) . "'";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$acp_modules->module_class = $module_class;

		$msg = "Removal of module $module_basename, mode $module_mode";
		if( $cats_only )
		{
			$cat_name = ( isset($user->lang[$mode_details['title']]) ) ? $user->lang[$mode_details['title']] : $mode_details['title'];
			$msg = "Removal of menu category $cat_name";
		}

		if( empty($rows) )
		{
			add_result($msg, "This could not be removed because it was not already installed.");
			continue;
		}
		foreach($rows as $v)
		{
			$result = $acp_modules->delete_module($v['module_id']);
			if( !empty($result) )
			{
				add_result($msg, $result[0]);
			}
			else
			{
				add_result($msg);
			}
		}
	}
}

function check_for_info($module_class, $module_basename)
{
	global $phpbb_root_path, $phpEx;
	$module_file = $phpbb_root_path . 'includes/' . $module_class . '/info/' . $module_class . '_' .$module_basename . '.' . $phpEx;
	if( !@file_exists($module_file) )
	{
		add_result("Module $module_basename", "The module's info file has not been uploaded. The module cannot be edited without the info file.");
		return FALSE;
	}
	return TRUE;
}

function check_for_installed($mode_details, $module_class, $parents)
{
	global $db;

	$parent_sql = '';
	if( !empty($parents) )
	{
		if( !is_array($parents) )
		{
			$parents = array($parents);
		}
		$parent_sql = ' AND ' . $db->sql_in_set('parent_id', $parents);
	}
	$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape($mode_details['title']) . "'
				AND module_class = '" . $db->sql_escape($module_class) . "'";
	$sql .= $parent_sql;
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	if( empty($rows) )
	{
		return FALSE;
	}
	
	return TRUE;
}

function add_modules($new_modules)
{
	global $db, $phpbb_root_path, $phpEx, $acp_modules;

	if( empty($new_modules) )
	{
		return;
	}
	foreach($new_modules as $k=>$v)
	{
		// Check if module name and mode exist...
		$module_class		= $v['class'];
		$module_basename	= $v['basename'];
		if( !check_for_info($module_class, $module_basename) )
		{
			continue;
		}
		$fileinfo = $acp_modules->get_module_infos($module_basename, $module_class);
		$fileinfo = $fileinfo[$module_basename];

		if( isset($fileinfo['new_parents']) )
		{
			install_modules($fileinfo['new_parents'], $module_class, '', true);
		}

		if( !empty($fileinfo['modes']) )
		{
			install_modules($fileinfo['modes'], $module_class, $module_basename);
		}
	}
}

function install_modules($modules, $module_class, $module_basename, $cats_only = FALSE)
{
	global $acp_modules, $user;
	$install_basename = $module_basename;
	foreach($modules as $module_mode => $mode_details)
	{
		$install_mode = $module_mode;
		// We need to get the parent for the mode.
		$parents = get_cat_ids($mode_details, $module_class);

		$msg = "Addition of module $module_basename, mode $module_mode";
		if( $cats_only )
		{
			$module_mode			= '';
			$mode_details['auth']	= '';
			$install_basename		= '';
			$install_mode			= '';
			$cat_name = ( isset($user->lang[$mode_details['title']]) ) ? $user->lang[$mode_details['title']] : $mode_details['title'];
			$msg = "Addition of menu category $cat_name";
		}

		foreach($parents as $parent_id)
		{
			// Check for an already installed instance of this module
			// under this parent. If it is already present, we don't install
			// again.
			if( check_for_installed($mode_details, $module_class, $parent_id) )
			{
				break;
			}
			$module_data = array(
				'module_basename'	=> $install_basename,
				'module_enabled'	=> 1,
				'module_display'	=> (isset($mode_details['display'])) ? $mode_details['display'] : 1,
				'parent_id'			=> $parent_id,
				'module_class'		=> $module_class,
				'module_langname'	=> $mode_details['title'],
				'module_mode'		=> $install_mode,
				'module_auth'		=> $mode_details['auth'],
			);

			$errors = $acp_modules->update_module_data($module_data, true);
			if( !empty($errors) )
			{
				add_result($msg, $errors[0]);
				break;
			}

			add_result($msg);
		}
	}
}

function process_sql()
{
	global $action, $sql, $db, $db_errors;
	if( empty($sql[$action]) )
	{
		return;
	}

	foreach($sql[$action] as $v)
	{
		if( !$result = $db->sql_query($v) )
		{
			$error = $db->sql_error();
			add_result($v, $error['message']);
		}
		else
		{
			add_result($v);
		}
	}
}

function wrap_up()
{
	global $cache, $action, $mod;
	add_log('admin', "<strong>Executed a modification database installer</strong><br />{$mod['name']} $action");

	// Now we will purge the cache.
	// This is necessary for any inserted or removed configuration settings
	// to take affect.
	$cache->purge();
	add_log('admin', 'LOG_PURGE_CACHE');
}

function add_result($item, $msg = '')
{
	global $results, $db_errors;
	$str = '<li>' . htmlspecialchars($item) . '<br /><span class="';
	if( !empty($msg) )
	{
		$str .= 'error">Failed due to error: ' . $msg;
		$db_errors = TRUE;
	}
	else
	{
		$str .= 'success">Completed successfully!';
	}
	$str .= '</span></li>';
	$results[] = $str;
}

function add_error_note()
{
	global $mod, $db_errors, $action;

	if( !$db_errors )
	{
		return '';
	}

	$site_string = $error_text = '';
	if( !empty($mod['website']) && !empty($mod['sitename']) )
	{
		$site_string = '<a href="' . $mod['website'] . '">' . $mod['sitename'] . '</a> or ';
	}

	$error_text .= ' If any error messages are listed above, a problem was encountered during the ';
	
	switch( $action )
	{
		case 'install':
			$error_text .= 'install. Any errors mentioning that a table already exists or duplicate entries or column names are often the result of running the install a second time by accident. Usually these errors can be ignored unless other problems appear when using the modification.';
		break;
		case 'upgrade':
			$error_text .= 'upgrade and some portions of the modification may not have been upgraded.';
		break;
		case 'uninstall':
			$error_text .= 'uninstall and some portions of the modification may not have been uninstalled.';
		break;
	}
	$error_text .= ' If you need assistance in troubleshooting these errors, please visit the support forums at ' . $site_string . ' <a href="http://www.phpbbhacks.com">phpBBHacks.com</a>.';

	return $error_text;
}

echo $page_head;
echo $page_text;
if( !empty($action) )
{
	wrap_up();
	echo add_error_note();		
	echo $page_postaction;
}
echo $page_tail;
?>