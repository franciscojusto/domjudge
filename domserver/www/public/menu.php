<nav><div id="menutop">
<a href="../../index.php" accesskey="h">home</a>
<a href="index.php" accesskey="h">contest home</a>
<?php
$path = $_SERVER['DOCUMENT_ROOT'];

if ( have_problemtexts() ) {
	echo "<a href=\"problem.php\" accesskey=\"p\">problems</a>\n";
}
logged_in(); // fill userdata
if ( checkrole('team') ) {
	echo "<a target=\"_top\" href=\"../team/\" accesskey=\"t\">→team</a>\n";
	echo '<a href ="../../forum">forum</a>';
}
if ( checkrole('jury') || checkrole('balloon') ) {
	echo "<a target=\"_top\" href=\"../jury/\" accesskey=\"j\">→jury</a>\n";
	echo '<a href ="../../forum">forum</a>';
}
if ( !logged_in() ) {
	echo "<a href=\"login.php\" accesskey=\"l\">login</a>\n";
}
?>
</div></nav>
