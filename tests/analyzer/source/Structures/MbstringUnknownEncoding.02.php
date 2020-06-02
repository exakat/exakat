<?php
const UUENCODE = 1;
const HIDDEN = 'iso-8859-1';

mb_regex_encoding('CP936CP936'); 
mb_regex_encoding('CP936'); 

mb_check_encoding ( $string, 'uft-9' );
mb_check_encoding ( $string, 'utf-8' );

$str = mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
$str = mb_convert_case($str, MB_CASE_UPPER, "UTF-89");

mb_stripos($text," ", "latin1"); 
mb_stripos($text," ", "latin9"); 

echo \mb_strimwidth("Hello World", 0, 10, "...", 'UUENCODE');
echo \mb_strimwidth("Hello World", 0, 10, "...", 'UUENCODE2');

echo \mb_strimwidth("Hello World", 0, 10, "...", UUENCODE);
echo \mb_strimwidth("Hello World", 0, 10, "...", ($x = UUENCODE));
echo \mb_strimwidth("Hello World", 0, 10, "...", HIDDEN);


?>