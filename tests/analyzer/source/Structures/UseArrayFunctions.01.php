<?php

foreach($array_push as $b) { array_push($c, $b);};

foreach($append as $b) { $c[] = $b; };

foreach($addition as $b) { $c[] = $b + 3; };

foreach($multiplication as $b) { $c = $b * 3; };

foreach($functioncall as $b) { foo($b); };

foreach($concatenation as $b) { $c = $c . $b; };

foreach($two as $b) { $a = 1; $c = $b + 2; };

foreach($ifthen as $b) { 
    if ($b == 1) {
        $c[] = $b;
    } else {
        $d[] = $b;
    }
 };

?>