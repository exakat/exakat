<?php
$url = 'a#b';
list($a , $b) = preg_split('\#', $url);
list($a , $b) = preg_split('/\#/', $url);
list($a , $b) = preg_split('/\#/S', $url);
list($a , $b) = preg_split('/\#/sS', $url);
list($a , $b) = preg_split('/\#/sx', $url);
print $a;
print $b;
?>
