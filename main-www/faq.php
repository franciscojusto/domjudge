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

		  <div id="accordion">
			  <h3>What are rounds?</h3>
			  <div>
			    <p>
			    Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
			    ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
			    amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
			    odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
			    </p>
			  </div>
			  <h3>How are points awarded?</h3>
			  <div>
			    <p>
			    Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
			    purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
			    velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
			    suscipit faucibus urna.
			    </p>
			  </div>
			  <h3>Are there prizes?</h3>
			  <div>
			    <p>
			    Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
			    Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
			    ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
			    lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
			    </p>
			    <ul>
			      <li>Grand Prize : $3910510581.00</li>
			      <li>Runner Up : Ipad Mini</li>
			      <li>Honorable Mentions : $25 Outback gift card</li>
			    </ul>
			  </div>
			  <h3>How many people can be on a team?</h3>
			  <div>
			    <p>
			    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
			    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
			    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
			    mauris vel est.
			    </p>
			    <p>
			    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
			    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
			    inceptos himenaeos.
			    </p>
			  </div>
			  <h3>What languages are supported?</h3>
			  <div>
			    <p>
			    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
			    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
			    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
			    mauris vel est.
			    </p>
			    <p>
			    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
			    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
			    inceptos himenaeos.
			    </p>
			  </div>
			  <h3>What does Ultimate Software do?</h3>
			  <div>
			    <p>
			    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
			    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
			    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
			    mauris vel est.
			    </p>
			    <p>
			    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
			    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
			    inceptos himenaeos.
			    </p>
			  </div>
		</div> 

		</div>
    </div>


</div>

</body>
</html>
