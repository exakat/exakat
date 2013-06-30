<?php
function a($b = true) {

	echo <<< C
D
C;
	ob_flush();
	exit();
}
?>