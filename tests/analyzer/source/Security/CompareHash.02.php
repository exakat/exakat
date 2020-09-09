<?php

use function hash_hmac_file as foo;

hash_hmac_file($algo1, $filename, $key) == 0;
foo($algo2, $filename, $key) == 0;
$x->foo($algo3, $filename, $key) == 0;
if(foo($algo4, $filename, $key)) {}


?>