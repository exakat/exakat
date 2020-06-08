<?php

function a(int $to = 2) {
$a = 1;
global $a;
static $a;
}
?>
