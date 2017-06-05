<?php

if ($x = strpos('ab1', 'b') == false) {}
if ( ($x = strpos('ab2', 'b')) == false) {}

if ( $x = (strpos('ab3', 'b') == false)) {}

if ( $x = (strpos('ab4', 'b') == 'false')) {}


?>