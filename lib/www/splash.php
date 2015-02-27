<?php

//function putSplashPage($cdata) {
    $now = now();
    $contestStarted = difftime($cdata['starttime'],$now) <= 0;

    if (! $contestStarted) { 
        echo '
<!-- this is the splash screen that displays company information -->

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>

<div id="splash" class="splash">
    <p>
<b>Ultimate Software</b> is a leading provider of cloud-based human capital management (HCM) solutions for businesses. For over 20 years, it has driven us to create the innovative products and services we offer today. As a result, we enable our clients to put their people first—helping them build the people-centric environments they need to grow and meet their business goals. 
Ultimate has more than 2,700 customers with employees in 160 countries, including Adobe Systems Incorporated, Culligan International, Major League Baseball, Pep Boys, Texas Rangers Baseball, and Texas Roadhouse. 

Our focus on people is evident and award-winning. Ultimate Software is ranked #20 on FORTUNE magazine’s 2014 list of 100 Best Companies to Work For. Ultimate is the only HCM provider on this prestigious list, which recognizes companies that have exceptional workplace cultures. Previously, Ultimate was ranked #9 on FORTUNE’s 2013 list and #25 on its 2012 list. 
    </p>

    <p>Ultimate Software is called <a href=" http://bit.ly/1f8OXdA" target="_blank">The Google of South Florida</a></p>
    <p>Ultimate is #8 on the Forbes Most Innovative Growth Companies: <a href="http://www.forbes.com/growth-companies/list/" target="_blank">See Here</a> </p>
    <p> Ultimate Software is named as one of the 50 most engaged Workforces in America:
        <a href="http://www.achievers.com/engaged/winners/2015" target="_blank">See Here</a></p>

<p>
 And there’s even more great news and accolades for Ultimate —Ultimate was chosen as one of 20 companies to be featured in InformationWeek’s, “20 Great Ideas To Steal In 2014” article, among Boeing, John Deere, McKesson, and other large, successful companies. View the feature <a href="http://ubm.io/1h2qhbC" target="_blank">here</a>
</p>

<h3>Here are a few vidoes about our culture:</h3>
<ul style="margin: 0; padding: 0; white-space:nowrap;">
    <li style="display:inline-block; width:50%; list-style-type:none;">
        <object height="315" width="100%" data="https://www.youtube.com/embed/9yQDGpX_7Zs"></object>    
    </li>
    <li style="display:inline-block; width:50%; list-style-type:none;">
        <object height="315" width="100%" data="https://www.youtube.com/embed/qUDmnT5qeNw"></object>
    </li>
</ul>

<div class="fb-like-box" data-href="https://www.facebook.com/UltimateSoftware" data-colorscheme="light" data-show-faces="false" data-header="true" data-stream="false" data-show-border="true"></div>

</div>';
    }
//}

?>
