<?php

$a = ['a' => range(3, 10)];
foreach($array as $k => [1 => $a2, 2 => $b2, 3=> [ $c2, $d2, [$e2, $f2, [$g2]]]]){
    print "$a => $b + $c + $d";
}

foreach($array as $k => list($a2, $b2, list( $c2, $d2, list($e2, $f2, list($g2))))){
    print "$a => $b + $c + $d";
}

?>