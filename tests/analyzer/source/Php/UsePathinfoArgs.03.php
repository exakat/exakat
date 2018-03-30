<?php

$extension = substr(strrchr($path, "."), 1);
$extension = array_pop(explode('.', $path));
$extension = array_pop(split('.', $path));

$extension = array_shift(split('.', $path));
$extension = substr(strrchr($path, "."), 2);
$extension = substr(strrchr($path, ".."), 2);

?>