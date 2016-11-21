<?php

$x = [1,2,3];

foreach($x as $a1 => $b1) {
    print "$a1 ".(++$a1)."\n";
}

foreach($x as $a2 => $b2) {
    unset($a2);
}

foreach($x as $a3 => $b3) {
    echo $a3." ".$b3."\n";
}

foreach($x as $a4 => $b4) {
    echo $a4." ".(++$b4)."\n";
}

foreach($x as $a5 => &$b5) {
    echo $a5." ".(++$b5)."\n";
}

foreach($x as $b6) {
    echo $b6." ".(++$b6)."\n";
}

foreach($x as &$b7) {
    echo $b7." ".(++$b7)."\n";
}

?>