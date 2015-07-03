<?php

if (($a = $b) == $c) { print "Equal\n";}

if ($a = $b == $c) { print "Equal 2\n";}

if ($a = ($b == $c)) { print "Equal 3\n";}

if ($e > ($f += $f)) {print ">\n";}

if ($e > $f += $f) {print "> NP\n";}

?>