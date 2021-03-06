<?php
/**
 * Produce a total score. Call with parameter 'static' for
 * output suitable for static HTML pages.
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */
session_start();
if (isset($_SESSION['redirect_url']) && $_SESSION['redirect_url'] != '')
{
    header('Location: ./'.$_SESSION['redirect_url']);
    unset($_SESSION['redirect_url']);
    exit;
}

require('init.php');
$title="Scoreboard";
// set auto refresh
$refresh = setPageRefresh($cdata);

// parse filter options
$filter = array();
if ( !isset($_GET['clear']) ) {
	foreach( array('affilid', 'country', 'categoryid') as $type ) {
		if ( !empty($_GET[$type]) ) $filter[$type] = $_GET[$type];
	}
	if ( count($filter) ) $refresh .= '?' . http_build_query($filter);
}

$menu = true;
require(LIBWWWDIR . '/header.php');

$isstatic = @$_SERVER['argv'][1] == 'static' || isset($_REQUEST['static']);

if ( ! $isstatic ) {
	echo "<div id=\"menutopright\">\n";
	putClock();
	echo "</div>\n";
}

// call the general putScoreBoard function from scoreboard.php
putScoreBoard($cdata, null, $isstatic, $filter);

// ouput the splash page
//putSplashPage($cdata);


echo "<script type=\"text/javascript\">initFavouriteTeams();</script>";

require(LIBWWWDIR . '/footer.php');
