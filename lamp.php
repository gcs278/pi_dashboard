<?php
	$q=$_GET["on"];
	if ( $q == 1 ) {
		exec("gpio write 7 1");
	}
	else {
		exec("gpio write 7 0");
	}
?>
