<!DOCTYPE html PUBLIC "~//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtmll/DTD/xhtmll-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<title>Forgot Username</title>
	<script type='text/javascript' src='../../../main-www/source/scripts/gen_validator31.js'></script>
</head>
<body>
	<h1>Forgot Username</h1>		
	<?php
	require_once("../../../main-www/source/include/class.phpmailer.php");	
	require_once("../../../main-www/source/include/class.smtp.php");
//	require_once("../../../main-www/source/include/membersite_config.php");
	require_once("../../../main-www/source/include/private_config.php");
	require_once("../../../main-www/source/include/formvalidator.php");
//	include("../../../main-www/source/include/fg_membersite.php");

	require_once("init.php");
	$message ="";
	$validform= TRUE;
	if ($_SERVER['REQUEST_METHOD']==="POST")
	{
		$validator = new FormValidator();
		$validator->addValidation("email", "req", " Please input an Email");
		$validator->addValidation("email", "email", " Please input a valid Email Address");
		if(!$validator->ValidateForm()) //Validate email
		{
			$error='';
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err)
			{
				$error .= $inpname.':'.$inp_err."\n";
			}
			//$this->HandleError($error);
		/*	var_dump($error);
			foreach($error as &$err)
			{*/
				echo $error."</br>";
		//	}
			$validform = FALSE;
		}
		/*else
		{
			var_dump($_POST);*/
		if($validform)//filter_var($_POST['email']))//if($_POST['email'] != "")
		{
			$email = $_POST['email'];	
			$query = $DB->q('table select * from user where email=%s', $email);
			$user_rec= array();									
			if(!empty($query))
			{	
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
				$mailer->Subject = "Ulticoder - Forgot Username";
				$mailer->From = "mail@ulticoder.com";
				$mailer->Body = "Hi ".$query[0]['name']."\r\n\r\n"."Your username is: ".$query[0]['username'].
						"\r\n\r\nRegards,\r\nWebmaster\r\nUltiCoder";
				
				if($mailer->Send())
				{
					$message = "An email has been sent.";
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
		/*else
		{
			$message = "Please enter your email";
		}*/
	if($message != "")
	{
		echo $message;
	}
	//exit();
	}  // End of $_POST['email'] IF
	?>
	<p>Please enter your email to have your username sent to you.</p>

	<form action="forgot-username.php" method="POST">

	<div class='container'>
	<label for="email">Email:</label>
		<div class='input'>
		<input name="email" type="text" id="email" value="" size="15" maxlength="50" accesskey="u" autofocus /><br/>
		<span id="forgot_username_email_errorloc" class='error'></span>
		</div>
	</div>
	<div class='container'>
		<div class='input'>
		<input type="submit" value="Retrieve Username" name="Submit"/>
		</div>
	</div>
	</form>
	</br>
	<a href='login.php'>Login</a> | <a href='forgot-password.php'>Forgot Password</a>

</body>
