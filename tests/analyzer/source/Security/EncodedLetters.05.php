<?php

$a = 'B';

$unicode = "\u{0011}";
$unicode = "\u{011}";
$unicode = "\u{11}";
print $a.$unicode;
print "\n";

$unicode = "\u{041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{0041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{00041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{0000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{00000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{00000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{000000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{0000000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{00000000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{000000000041}";
print $a.$unicode;
print "\n";

++$a;
$unicode = "\u{00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000041}";
print $a.$unicode;
print "\n";

?>
