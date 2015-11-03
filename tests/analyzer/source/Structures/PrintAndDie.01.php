<?php

print "a";
die();

print "b";
exit;

echo "c";
die();

echo "d";
exit;

print "e";
$a++;
exit;

print "f";
if ($a) { die(); }

?>