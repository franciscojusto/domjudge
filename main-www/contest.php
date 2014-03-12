<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Contest - Ultimate Software Programming Competition, Win a Trip to South Beach</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href='https://fonts.googleapis.com/css?family=Average' rel='stylesheet' type='text/css'>
<!--
    <script type="text/javascript">
function cdtd() {
	var xmas = new Date("March 8, 2014 13:00:00");
	var now = new Date();
	var timeDiff = xmas.getTime() - now.getTime();
	if (timeDiff <= 0) {
        clearTimeout(timer);
		document.write("Enroll Now");
		// Run any code needed for countdown completion here
    }
	var seconds = Math.floor(timeDiff / 1000);
	var minutes = Math.floor(seconds / 60);
	var hours = Math.floor(minutes / 60);
	var days = Math.floor(hours / 24);
	hours %= 24;
    minutes %= 60;
    seconds %= 60;
	document.getElementById("daysBox").innerHTML = days;
	document.getElementById("hoursBox").innerHTML = hours;
	document.getElementById("minsBox").innerHTML = minutes;
	document.getElementById("secsBox").innerHTML = seconds;
	var timer = setTimeout('cdtd()',1000);
}
</script>
<link href="timer.css" rel="stylesheet" type="text/css">
-->
</head>
<body>
<div id="strip"></div>
<div id="container">

<?php include("topnav.html");?>


<fieldset>
<legend>Contest</legend>
<!--
<div id="timeBox">
<h3>Our next competition begins in:</h3>
<div id="daysWrapper">
  <div id="days">Days</div>
	<div id="hours">Hours</div>
	<div id="mins">Min</div>
	<div id="secs">Sec</div>
</div>
<div id="countdownBox">
  	<div id="daysBox"></div>
	<div id="hoursBox"></div>
	<div id="minsBox"></div>
	<div id="secsBox"></div>
</div>

</div>
-->
<script type="text/javascript">cdtd();</script>
<div class="content">
<a href="domjudge/public" class="button">
<input type="submit" value="Enter" style="width:30%;height:60px;" class="center peter-river-flat-button">
</a>
</div>
</fieldset>

</div>
<?php include_once("analyticstracking.php") ?>
</body>
</html>
