<?PHP
require_once('fg_membersite.php');
require_once('private_config.php');

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('Ulticoder');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail($p_admin_email);

//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(  $p_hostname,
			$p_dbusername,
			$p_dbpassword,
                      /*database name*/$p_dbname,
                      /*table name*/$p_tablename);

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey("$p_randomkey");


?>
