<?php

// the first argument is recognized as an URL
fopen('/tmp/my/file.txt', 'r+');
fopen('tmp/my/file.txt', 'r+');
fopen('tmp/my/file', 'r+');

// the string argument  is recognized as an URL
$source = 'https://www.other-example.com/';
$source = 'http://www.other-example.com/';
$source = '/www.other-example.com/';
$source = '/www.other-example.com';

?>