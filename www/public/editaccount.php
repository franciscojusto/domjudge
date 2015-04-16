<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title>Account Settings</title>
	<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
</head>

<body>
	<h1>Account Settings</h1>
<?php
require('init.php');

session_start();

if(! logged_in()) {  
    $_SESSION['redirect_url'] = 'editaccount.php';
    header("Location: ./login.php");
    exit;
}

$username = $_SESSION['username'];
$q = $DB->q("table SELECT * FROM user WHERE username=%s", $username);


$userinfo->email = htmlentities($q[0]['email'], ENT_QUOTES);
$q = $DB->q("table SELECT * FROM user_info WHERE username=%s", $username);
$userinfo->addressln1 = htmlentities($q[0]['addressln1'], ENT_QUOTES);
$userinfo->addressln2 = htmlentities($q[0]['addressln2'], ENT_QUOTES);
$userinfo->zipcode = htmlentities($q[0]['zipcode'], ENT_QUOTES);
$userinfo->tshirtsize = htmlentities($q[0]['tshirtsize'], ENT_QUOTES);

if($userinfo->tshirtsize == "XXL")
{
	$userinfo->tshirtXXL = "selected = 'selected'";
}
else if($userinfo->tshirtsize == "XL")
{
	$userinfo->tshirtXL = "selected='selected'";	
}
else if($userinfo->tshirtsize == "Large")
{
	$userinfo->tshirtL = "selected='selected'";
}
else if($userinfo->tshirtsize == "Medium")
{
	$userinfo->tshirtM = "selected='selected'";
}
else if ($userinfo->tshirtsize == "Small")
{
	$userinfo->tshirtS = "selected='selected'";
}
else{
	$userinfo->tshirtEmpty = "selected='selected'";
}

$pass_errors = array();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	
	if($_POST['email'] == "")
	{
		$pass_errors['email'] = 'The email field is empty';
	}
	else
	{
		$q = $DB->q("table SELECT * FROM user WHERE email=%s", $_POST['email']);
		if($q[1] == null && ($q[0] == null || $q[0]['username'] == $username)){
			$email =  htmlentities($_POST['email'], ENT_QUOTES);
		}
		else
		{
			$pass_errors['email'] = 'The email entered already exist';
		} 	
	}
	
	$addressln1 = htmlentities($_POST['addressln1'], ENT_QUOTES);
	$addressln2 = htmlentities($_POST['addressln2'], ENT_QUOTES);
	$zipcode = htmlentities($_POST['zipcode'], ENT_QUOTES);
	$tshirtsize = htmlentities($_POST['tshirtsize'], ENT_QUOTES);
	
	if(empty($pass_errors))
	{
		// check if the table already exist
		$q = $DB->q("table SELECT * FROM user_info WHERE username=%s", $username);

		if($q[0]['username'] == null){
			$q = $DB->q("INSERT INTO user_info (username, addressln1, addressln2, zipcode, tshirtsize) 
				VALUES (%s, %s, %s, %s, %s)", $username, $addressln1, $addressln2, $zipcode, $tshirtsize);
			$q = $DB->q("UPDATE user SET email=%s WHERE username=%s", $email, $username);
		}
		else
		{
			$q = $DB->q("UPDATE user_info SET addressln1=%s, addressln2=%s, zipcode=%s, tshirtsize=%s WHERE username=%s", $addressln1, $addressln2, $zipcode, $tshirtsize, $username);
		
			$q = $DB->q("UPDATE user SET email=%s WHERE username=%s", $email, $username);
		}
		echo "Information has been updated!";
		echo '</br><a href="index.php">Home</a>';	
	}
	else{
		echo $pass_errors['email'];
		echo '</br><a href="editaccount.php">Back</a>';
	}
	
	exit();

}// END if POST
?>
	<a href="change-password.php">Change Psssword</a>
	
	<form action="editaccount.php" method="post" accept="utf-8">
	<table>
	<tr>
		<tr><td><label for="email">Email: </label></td><td><input name="email" type="email" autofill="false" id="email" value='<?php echo $userinfo->email; ?>' size="15" autofocus required/></td></tr>	</tr>
	<tr><td><label for="addressln1">Address Line One: </label></td><td><input name="addressln1" pattern="[a-zA-Z0-9\s]*" autofill="false" id="addressln1" value='<?php echo $userinfo->addressln1; ?>' size="15" maxlength="50" autofocus/></td></tr>
	<tr><td><label for="addressln2">Address Line Two: </label></td><td><input name="addressln2" pattern="[a-zA-Z0-9\s]*" autofill="false" id="addressln2" value='<?php echo $userinfo->addressln2; ?>' size="15" maxlength="50" autofocus/></td></tr>

	<tr><td><label for="zipcode">ZIP Code: </label></td><td><input name="zipcode" id="zipcode" pattern="[a-zA-Z0-9\s]*"  autofill="false" value='<?php echo $userinfo->zipcode; ?>' size="15" maxlength = "10" autofocus/></td></tr>

	<tr><td><label for="tshirtsize">T-Shirt Size:</label></td><td><select name="tshirtsize" id="tshirtsize" value='<?php echo $userinfo->tshirtsize; ?>'  autofocus>
		<option value="E" <?php echo $userinfo->tshirtEmpty; ?>></option>
		<option value="XXL" <?php echo $userinfo->tshirtXXL; ?>>XX-Large</option>
		<option value="XL" <?php echo $userinfo->tshirtXL; ?>>X-Large</option>
		<option value="Large" <?php echo $userinfo->tshirtL; ?>>Large</option>
		<option value="Medium" <?php echo $userinfo->tshirtM; ?>>Medium</option>
		<option value="Small" <?php echo $userinfo->tshirtS; ?>>Small</option>
	</select>
	</td></tr>
	
	<tr><td><input type="submit" value="Save Setting" /></td></tr>
	</table>
	</form>	

	</br><a href='index.php'>Home</a></br>
</body>

</script>


