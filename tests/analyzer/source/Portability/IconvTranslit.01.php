<?php

$string = '€uro';
print $string = iconv('utf-8', 'utf-8//TRANSLit', $string);
print $string = iconv('utf-8', 'ascii//translit', $string);
print $string = iconv('utf-8', 'utf-8//translitt', $string);
print $string = iconv('utf-8', 'ascii/translit', $string);
print $string = iconv('utf-8', 'ascii/bouh', $string);

?>