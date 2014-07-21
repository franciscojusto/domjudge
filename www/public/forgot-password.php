	
<!DOCTYPE html PUBLIC "~//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtmll/DTD/xhtmll-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<title>Forgot Password</title>
</head>
<body>
	<h1>Forgot Password</h1>		
	<?php
	require_once("../../../main-www/source/include/class.phpmailer.php");	
	require_once("../../../main-www/source/include/class.smtp.php");
	require_once("../../../main-www/source/include/private_config.php");
	require_once("../../../main-www/source/include/formvalidator.php");
	require_once("init.php");
	$message ="";
	$sent = false;
	$validform= true;
	if ($_SERVER['REQUEST_METHOD']==="POST")
	{
		$validator = new FormValidator();
		$validator->addValidation("email", "email", " Please input a valid Email Address");
		$validator->addValidation("email", "req", " Please input an Email");
		
		if(!$validator->ValidateForm())
		{
			$error='';
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err)
			{
				$error .= $inpname.':'.$inp_err."\n";
			}

			echo $error."</br>";
			$validform = false;
		}
		if($validform)
		{	
			$email = $_POST['email'];	
			$query = $DB->q('table select * from user where email=%s', $email);
			$user_rec= array();									
			if(!empty($query))
			{	
				$p = substr(md5(uniqid()),0,10);
				$username = $query[0]['username'];
				$password = $username.'#'.$p;
				$salt = sha1(md5($password));
				$q = $DB->q("update user set password=%s where username=%s", md5($salt.$password), $query[0]['username']);
				$q = $DB->q("update phpbb_users set user_password=%s where user_email=%s", phpbb_hash($p), $email);			
				global $p_emailuser;	
				global $p_emailpassword;
				$mailer= new PHPMailer();
				$mailer->IsSMTP();
				$mailer->SMTPDebug = 1;		
				$mailer->SMTPAuth = true;
				$mailer->Host = "smtp.postmarkapp.com";
				$mailer->Port = 25;
				$mailer->IsHTML(false);
				$mailer->Username = "$p_emailuser";
				$mailer->Password = "$p_emailpassword";
				$mailer->CharSet = 'utf-8';
				$mailer->AddAddress($query[0]['email'], $query[0]['name']);
				$mailer->Subject = "Ulticoder - Reset Password";
				$mailer->From = "mail@ulticoder.com";
				$mailer->Body = "Hi ".$query[0]['name']."\r\n\r\n"."Your temporary password is: ".$p.
						"\r\n\r\nRegards,\r\nWebmaster\r\nUltiCoder";
				
				if($mailer->Send())
				{
					$message = "Your password has been reset and an email has been sent.";
					$sent = true;
				}
				else
				{
					$message = "Email error occured.";
				}
			}
			else
			{
				$message = "The submitted email address does not match any on file.";
			}
		}
		
		if($message != "")
		{
			echo $message;
		}
	}  // End of $_POST['email'] IF
	?>
	<p>Please enter your email to have a temporary password sent to you.</p>

	<form action="forgot-password.php" method="POST">

	<div class='container'>
	<label for="email">Email:</label>
		<div class="input">
		<input name="email" type="text" id="email" value="" size="15" maxlength="50" accesskey="u" autofocus /></br>
		<span id='forgot-password-errorloc' class='error'></span>
		</div>
	</div>
	<div class='container'>
		<div class='input'>
		<input type="submit" name='Submit' value="Reset Password" />
		</div>
	</div>
	</form>
	</br>
	<a href='login.php'>Login</a> | <a href='forgot-username.php'>Forgot Username</a>

</body>
