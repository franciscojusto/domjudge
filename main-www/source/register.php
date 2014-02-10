<?PHP
	require_once("./include/membersite_config.php");
	require_once('./include/recaptchalib.php');
	require_once('./include/private_config.php');

	if ($_POST['Submit']) {
		$resp = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			echo '<script language="javascript">';
			echo 'alert("Invalid captcha")';
			echo '</script>';
		} else {
			// Your code here to handle a successful verification
			if($fgmembersite->RegisterUser())
			{
				$fgmembersite->RedirectToURL("thank-you.html");
			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Register</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>      
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
	<fieldset>
		<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<input type='hidden' name='submitted' id='submitted' value='1'/>

		<div class='short_explanation'>* required fields</div>
		<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

		<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
		<div class='container'>
		    <label for='name' >Your Full Name*: </label>
			<div class="input">
		    <input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
		    <span id='register_name_errorloc' class='error'></span>
			</div>
		</div>
		<div class='container'>
		    <label for='email' >Email Address*:</label>
			<div class="input">
		    <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
		    <span id='register_email_errorloc' class='error'></span>
			</div>
		</div>
		<div class='container'>
		    <label for='username' >UserName*:</label>
			<div class="input">
		    <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
		    <span id='register_username_errorloc' class='error'></span>
			</div>
		</div>
		<div class='container'>
		    <label for='password' >Password*:</label>
			<div class="input">
		    <div class='pwdwidgetdiv' id='thepwddiv' ></div>
		    <noscript>
		    <input type='password' name='password' id='password' maxlength="50" />
		    </noscript>    
		    <div id='register_password_errorloc' class='error' style='clear:both'></div>
			</div>
		</div>
		<div class='container'>
		    <label for='password2' >Re-enter password*:</label>
			<div class="input">
		    <input type='password' name='password2' id='password2' maxlength="50"/>
		    <div id='register_password2_errorloc' class='error' style='clear:both'></div>
			</div>
		</div>
		<div class='container'>
			<label style="visibility:hidden">.</label>
			<div class="input">
			<?php echo recaptcha_get_html($publickey); ?>
			</div>
		</div>
		<div class='container'>
			<label style="visibility:hidden">.</label>
			<div class="input">
		    <input type='submit' name='Submit' value='Submit' />
			</div>
		</div>
		</form>
	</fieldset>
</div>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[
    var pwdwidget = new PasswordWidget('thepwddiv','password');
    pwdwidget.MakePWDWidget();

    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether(); 
    frmvalidator.addValidation("name","req","Please provide your name");
    frmvalidator.addValidation("name","name","Please provide a valid name");

    frmvalidator.addValidation("email","req","Please provide your email address");

    frmvalidator.addValidation("email","email","Please provide a valid email address");

    frmvalidator.addValidation("username","req","Please provide a username");
    frmvalidator.addValidation("username","alnum","Username may only consist of letters and/or numbers");
    
    frmvalidator.addValidation("password","req","Please provide a password");
    
    frmvalidator.addValidation("password2","req","Please re-enter the password");
// ]]>
</script>

<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
