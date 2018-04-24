<?php

$c = 'abc';

echo preg_replace('\'(a)\'si', 'A', $c);
echo preg_replace('\'(a\\)\'si', 'A', $c);
echo preg_replace('\"(a)\"si', 'B', $c);
echo preg_replace("\"(a)\"si", 'C', $c);
echo preg_replace("\\(a)\\si", 'D', $c);

?>