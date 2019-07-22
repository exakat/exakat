<?php

$a = ['a' => range(3, 10)];
foreach($array as $k => [$a, $b, $c]){
    print "$a => $b + $c + $d";
}

foreach($array as $k => list($a2, $b2, $c2)){
    print "$a => $b + $c + $d";
}

?>