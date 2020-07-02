<?php

// the first argument is recognized as an URL
fopen('php://memory', 'r+');
fopen('php://temp', 'r-');
fopen('zlib://my/file.txt', 'a+');
const A = 'expect://'. B;
const A2 = 'RAR://'. B;

// the string argument  is recognized as an URL
$source = 'ogg:/www.other-example.com/';
$source = 'ahttp://www.other-wrong-example.com/';
const V = 'phar:://'. B;

?>