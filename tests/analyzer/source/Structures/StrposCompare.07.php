<?php

if (preg_match('/asdf/', $a, $b)) {}
if (preg_match('/'.$s.'/', $a, $b)) {}
if (preg_match("/$s/", $a, $b)) {}
if (preg_match_nope("/$s/", $a, $b)) {}

?>