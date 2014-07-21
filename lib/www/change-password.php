

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title>Change Password</title>
	<script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
	<link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css"/>
	<script src="scripts/pwdwidget.js" type="text/javascript"></script>
</head>

<body>
	<h1>Change Password</h1>

<?php
require('init.php');
//include('../../../main-www/source/include/fg_membersite.php');

$pass_errors = array();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if(!empty($_POST['current']))
	{
		$current = $_POST['current'];
	}
	else
	{
		$pass_errors['current'] = 'Please enter your current password.</br>';
		echo 'Please enter your current password.</br>';
	}
	
	if(($_POST['pass1'] != "") && $_POST['pass2'] != "")
	{
		if($_POST['pass1'] === $_POST['pass2']) 
		{
			$p = $_POST['pass1'];
		}
		else
		{
			$pass_errors['pass1']= 'Your new password did not match confirmed password.</br>';
			echo 'Your new password did not match confirmed password.</br>';
		}
	
	}
	else
	{		
		$pass_errors['pass1'] = 'Please enter a valid new password.</br>';
		echo 'Please enter a valid new password.</br>';
	}

	if(empty($pass_errors))
	{
		session_start();
		$q = $DB->q('table select * from user where username=%s', $_SESSION['username']);

		$username = $q[0]['username'];
		$cur_pass = $username."#".$current;		
		$salt = sha1(md5($cur_pass));
		if($q[0]['password'] === md5($salt.$cur_pass))
		{
		
			$new_pass = $username."#".$p;
			$salt = sha1(md5($new_pass));
			$q = $DB->q("update user set password=%s where username=%s", md5($salt.$new_pass), $username);
			$q = $DB->q("update phpbb_users set user_password=%s where username=%s", phpbb_hash($p), $username);
		
				echo '<h2>Your password has been changed.</h2>';
				echo "</br><a href='index.php'>Home</a></br>";
			//	require(LIBWWWDIR . '/footer.php');
				exit();
		}
		
		
		
		
		else	//INVALID PASSWORD
		{
			echo 'Your current password is incorrect.</br>';
		}
	
	} // End of if empty

}// END if POST
?>

	<p>Use the form below to change your password</p>
	<form action="change-password.php" method="post" accept="utf-8">
	<table>
	<tr>
		<td><label for="current">Current Password:</label></td>
	<!--	<div class="pwdwidgetdiv" id="thepwddiv"></div>
		<noscript>-->
		<td><input name="current" type="password" id="current" value="" size="15" maxlength="50" autofocus/></td>
		<!--</noscript>-->
	</tr>

	<tr><td><label for="pass1">New Password:</label></td><td><input name="pass1" type="password" id="pass1" value="" size="15" autofocus/></td></tr>

	<tr><td><label for="pass2">Confirm Password</label></td><td><input name="pass2" type="password" id="pass2" value="" size="15" autofocus/></td></tr>
	
	<tr><td><input type="submit" value="Change Password" /></td></tr>
	</table>
	</form>	

	</br><a href='index.php'>Home</a></br>
</body>

<script type='text/javascript'>
	var pwdwidget = new PasswordWidget('thepwddiv', 'password');
	pwdwidget.MakePWDWidget();

	var frmvalidator = new Validator("change password");
	frmvalidator.EnableOnPageErrorDisplay();
	frmvalidator.EnableMsgsTogether();
	frmvalidator.addValidation("current","req","Please provide your current password");

	frmvalidator.addValidation("pass1","req","Please provide a new password");
	frmvalidator.addValidation("pass2","req","Please confirm your new password");
</script>
