<?php 

fopen('php://input', 'r');

file_put_contents("php://output", 'c');

file_get_contents("php://fd".$b, 'c');

// those are OK
glob('php://memory', 'r'); // 2nd is literal

unlink("php://filter");

rmkdir($e."php://stdout");

yes('php://stdin');
no('php://stdout');
glob('php://fd34', '4');
unlink('D:\\A\B\C');
opendir('C:\\htdocs');


?>