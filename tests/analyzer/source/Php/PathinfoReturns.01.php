<?php

list(,, $extension, $filename) = array_values(pathinfo($filename2));
[,, $extension, $filename] = array_values(\pathinfo($filename3));

[,, $extension, $filename] = array_keys(pathinfo($filename4));
$a = array_values(pathinfo($filename5));
list($a) = array_values(pathinformation($filename6));
list($a) = myclass::array_values(pathinfo($filename7));

?>