<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Welcome to Ulticoder!</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link href='http://fonts.googleapis.com/css?family=Average' rel='stylesheet' type='text/css'>
	<script src="jquery-1.10.2.js"></script>
	<script src="jqueryui/js/jquery-ui-1.10.3.custom.js"></script>		
	<script>
		$(function() {
		 	$( "#accordion" ).accordion();
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

<div id="container">

	<?php include("topnav.html"); ?>

    <div class="content">
		<div class="childelem" style="float:left;padding-top:2%">
<pre>
		Specs:
		compile time: 30 seconds
		memory limit: 524288KB, 512MB (includes JavaVM, which is like 300MB
		output file size limit: 4096KB (filesize_limit)
		source file size limit: 256KB
                source file #files: 1

		time limit: depends on problem, ~5 seconds usually
		java has 1.5x extra time
		
		time penalty on wrong submission: 20min
</pre>
		</div>

    </div>

</div>

</body>
</html>
