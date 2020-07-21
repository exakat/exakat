<?php
$im = image(1,2);
imagegif($im, 'a'.'b');
imagegif($im, <<<URL
some URL
URL
);

const A = '/path/as2/concat.php';
const b = '/path/as'.'/concat.inc';
const C = <<<HERE
/path/as/heredoc.c
HERE;
const D = 'Not a path';
const E = '/a/b/c/';

?>