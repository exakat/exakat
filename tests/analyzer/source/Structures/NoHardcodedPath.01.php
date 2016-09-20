<?php 

fopen('a', 'r');

file_put_contents("a$b", 'c');
file_put_contents("a{b}", 'c');

file_get_contents("a".$b, 'c');
file_get_contents("a".B, 'c');

// those are OK
glob(__DIR__, 'r'); // 2nd is literal

unlink("{$c}d");

rmkdir($e."f");

?>