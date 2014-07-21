<?php
require('init.php');

$topic=$_POST['topic'];
$detail=$_POST['detail'];
$name=$_POST['name'];
$email=$_POST['email'];
$datetime=date("d/m/y h:i:s");

$success = $DB->q("returnid insert into forum_question(topic,detail,name,email,datetime)values('$topic','$detail','$name','$email','$datetime')");

if($success){
	echo "<a href=\"main_forum.php\">View your topic</a>";
}
else {
	echo "<p>ERROR</p>";
}
?>

