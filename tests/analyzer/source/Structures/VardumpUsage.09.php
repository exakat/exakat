<?php

$a = array(1, 2.3, null, false);

echo print_r($a, 1);
echo print_r($a, true);
echo print_r($a, '1');
echo print_r($a, "1");
echo print_r($a, "abc");

?>