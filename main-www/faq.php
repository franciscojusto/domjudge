<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FAQ - Ultimate Software Programming Competition, Win a Trip to South Beach</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link href='https://fonts.googleapis.com/css?family=Average' rel='stylesheet' type='text/css'>
	<script src="jquery-1.10.2.js"></script>
	<script src="jqueryui/js/jquery-ui-1.10.3.custom.js"></script>		
	<script>
		$(function() {
		 	$( "#accordion" ).accordion({
			collapsible: true
			});
		 	$( ".selector" ).accordion( "option", "icons", { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" } );
			    var icons = {
			      header: "ui-icon-circle-arrow-e",
			      activeHeader: "ui-icon-circle-arrow-s"
			    };
			    $( "#accordion" ).accordion({ icons: icons });
			  });
	</script>
</head>
<body>
<div id="strip"></div>
<div id="container">

	<?php include("topnav.html"); ?>


<fieldset>
<legend>Frequently Asked Questions</legend>
    <div class="content">
		  <div id="accordion">
			  <h3>How do I register for the competition?</h3>
			  <div>
			    <p>
Simply visit our <a href="register.php">Registration</a> page to sign up. We will send you a confirmation email and you will be ready to compete! 
			    </p>
			  </div>
<h3>Who is able to participate?</h3>
			  <div>
			    <p>
			    The competition is open to contestants who are legal residents of the US or Canada and are 18 years of age or older at the time of entry.</p>
			  </div>
<h3>Where is the competition located?</h3>
			  <div>
			    <p>
The competition will be held entirely online, available at <a href="http://www.ulticoder.com/domjudge/public/">here</a>.
			    </p>
			  </div>
<h3>When is the competition?</h3>
			  <div>
			    <p>
The official date of competition is <b>April 4th 2015, 1PM - 5PM EST.</b>
			    </p>
			  </div>
<h3>What kind of problems can I expect?</h3>
			  <div>
			    <p>
See this <b><a href="../SAMPLE/Winner Winner/winner.zip">example</a></b> to get started. The problems in the competition are algorithm based, with points being awarded for efficiency rather than style. Topics can include any combination of the following: graph theory, combinatorics, number theory, game theory, data structures, calculus, string problems, and dynamic programming. See <b><a href="../results.php" target="_blank">here</a></b> for previous contests.
			    </p>
			  </div>
<h3>What are the constraints for my solution?</h3>
			  <div>
			    <p>
Each submission will be given 5s to run, with Java having an extra 2.5s.
There will also be an maximum memory usage limit of 256MB. These limits are intended to inspire creative ways of finding efficient solutions to hard problems.

The solution must be contained in one file, of size no greater than 256KB.

You may submit a solution to a problem an unlimited number of times, but each incorrect solution will dock you 20 penalty points.
			    </p>
			  </div>
<h3>What languages can I use?</h3>
			  <div>
			    <p>
Submissions will be accepted in Java 7, C, C++, Mono C#, Python (2.7), and Ruby (1.7)
			    </p>
			  </div>
<h3>What if I don't understand a part of the problem?</h3>
			  <div>
			    <p>
There will be opportunities to ask for clarifications. You can submit a clarification request for any problem or a piece of the system, and we will try to respond in a timely fashion. Please note that we cannot give any hints or insight into solutions. Should a wording in the problem statement be deemed ambiguous, all contestants will be notified of the clarification.
			    </p>
			  </div>
			  <h3>How are rankings determined?</h3>
			  <div>
			    <p>
The judging system will be similar to that of ACM. The contestants will be ranked based on the number of questions solved, and then by least sum of time taken to solve their submissions. There will be unlimited number of submissions, but each incorrect one will be 20 penalty points. 
			    </p>
			  </div>

		</div> 
    </div>
</fieldset>



</div>

<?php include_once("analyticstracking.php") ?>
</body>
</html>
