<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ultimate Software Programming Competition, Win a Trip to South Beach</title>
<link rel="stylesheet" type="text/css" href="style.css?ver=1.0">
<style type="text/css">
</style>
<script src="jquery-1.10.2.min.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>
<body>
<div id="fb-root"></div>

<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

<div id="strip"></div>
<div id="container">

<?php include("topnav.html");?>

<div class="content">

<h4 style="font-size:150%; float:left; display:block; width:98%; padding:1%; text-align:center;background-color:#5CA616; box-shadow: 2px 2px 2px   #888;">
Our next competition is on <b>April 4th 2015 1PM - 5PM EST.</b></br> See more information <a href="https://www.facebook.com/events/1620141204886533/" target="_blank">here</a>
</h4>

<div class="left" style="overflow:hidden"><iframe width="445" height="500" frameborder="0"
src="https://www.youtube.com/embed/9yQDGpX_7Zs?autoplay=0"></iframe>
</div>
<div class="right">
<div class="registerbox">
<!--
<a href="domjudge/public" class="button">
<input type="submit" value="Enter" style="width:100%;height:120px;" class="center peter-river-flat-button">
</a>
-->
<form class="flat-button" action="register.php" style="margin-bottom:10px">
<input type="submit" value="Register" class="flat-button">
</form>
</div>
<h4>Register for our next coding competition to win a trip for 2 to <em>South Beach</em>. Top 3 Winners will each get a trip to South Beach plus cash prizes.</h4>
<br><br>
<h3>What is UltiCoder?</h3>
<p><em>UltiCoder</em> brings together bright minded people who compete as <b>individuals</b> remotely throughout the US and Canada solving tough coding problems in an ACM style competition awarding prizes to top performers.</p><br>
<h3>Who are we?</h3>
<p>
Ultimate Software is a leading cloud provider of people management solutions for businesses across all industries. Ultimate Software is located in the Miami FL area and has been ranked a Fortune Top 100 Best Place to Work Three Years in a Row.
</p>
</div>
<div class="footer">
<div class="left">
<div class="fb-like" data-href="https://www.facebook.com/events/1620141204886533/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>

<!--	unhide the comment section when marketing begins	
<div class="fb-comments" data-href="https://www.facebook.com/events/1620141204886533/" data-numposts="5" data-colorshceme="light"></div>
-->   
</div>
<div> <img align="right" src="images/Sponsored-by-US.jpg" width="163" height="75"> </div>
</div>

</div>

<?php include_once("analyticstracking.php") ?>
</body>
</html>
