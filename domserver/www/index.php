<?php

/**
 * Switch a user to the right site based on whether they can be
 * authenticated as team, jury, or nothing (public).
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

<script type="text/javascript">
	
//Tracking Code Customizations Only
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-47503831-1']);
_gaq.push(['_setCookiePath', '/domjudge/public/index.php']);
_gaq.push(['_trackPageview']); 

</script>

require_once('configure.php');

require_once(LIBDIR . '/lib.error.php');
require_once(LIBDIR . '/lib.misc.php');
require_once(LIBDIR . '/use_db.php');
// Team login necessary for checking login credentials:
setup_database_connection();

require_once(LIBWWWDIR . '/common.php');
require_once(LIBWWWDIR . '/auth.php');

$target = 'public/';
if ( logged_in() ) {
	if     ( checkrole('team',false) ) $target = 'team/';
	elseif ( checkrole('jury') )       $target = 'jury/';
	elseif ( checkrole('balloon') )    $target = 'jury/balloons.php';
}

header('HTTP/1.1 302 Please see this page');
header('Location: ' . $target);
