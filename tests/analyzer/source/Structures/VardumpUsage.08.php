<?php

echo print_r($a, 1);

print '<pre>'. print_r($a, 1) . '</pre>';

print '<pre>'. var_export($a, true) . '</pre>';

echo print_r($a);

echo var_dump($a);

?>