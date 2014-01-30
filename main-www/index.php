<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Welcome to Ulticoder!</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href='http://fonts.googleapis.com/css?family=Average' rel='stylesheet' type='text/css'>
	<script src="jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>
<body>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47503831-1']);
  _gaq.push(['_setDomainName', 'ulticoder.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<div id="container">

	<?php include("topnav.html"); ?>

    <div class="content">
    <div class="left">
		<video width="100%" height="420" controls>
		  <source src="outtakes.mp4" type="video/mp4">\
		  Your browser does not support the video tag.
		</video>
    </div>
    <div class="right">
        <div class="registerbox">
        <form class="flat-button" action="register.php">
        <input type="submit" value="Register" class="flat-button">
        </form>
        <div class="label">Register to Win!</div>
        </div>
        <h4>What is UltiCoder?</h4>
        <p><em>UltiCoder</em> brings together bright minds to solve tough coding problems and provide the best solutions in a competition awarding prizes to top performers.</p>
        <h4>Who are we?</h4>
        <p>
        Ultimate Software is a team of professionals dedicated to putting people first providing cloud solutions for complex business problems.
        </p>
    </div>

</div>

</body>
</html>
