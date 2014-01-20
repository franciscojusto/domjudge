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

		<div class="childelem" style="float:left;width:60%;padding-top:2%;padding-left:20%">
		<iframe src="https://docs.google.com/spreadsheet/embeddedform?formkey=dHJOSHJxMU5jaUtwOEJ1M2VvMWxNRlE6MA" style="overflow:hidden;height:1000px;width:100%" height="1000px" width="100%" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
		</div>


</div>

</body>
</html>
