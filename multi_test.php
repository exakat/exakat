<?php

$size = 10;

for($size = 5; $size < 60; $size += 5) {
//for($size = 5; $size < 9; $size += 5) {
    shell_exec('php proto_test.php '.$size);
    $res = shell_exec('sh test.sh');
    $res = file_get_contents('time.txt');
//    unlink('time.txt');

    //print "|".$res."|";

    preg_match("/real\t(\d+)m(\d+).\d+/s", $res, $r);
    $time = $r[1] * 60 + $r[2];

    print $size."\t$time\n";

    $fp = fopen('test.log', 'a');
    fwrite($fp, $size."\t$time\n");
    fclose($fp);
}

?>