<?php
/**
 * Common page footer
 */
if (!defined('DOMJUDGE_VERSION')) die("DOMJUDGE_VERSION not defined.");

if ( DEBUG & DEBUG_TIMINGS ) {
	echo "<p>"; totaltime(); echo "</p>";
} ?>

</body>
<script>
$('#all_scores').oneSimpleTablePagination({rowsPerPage: 35});
</script>
</html>
