<?php 
foreach($contents as $id => $c) {
    if (get_class($c) != "A") { 
        unset($contents[$id]); 
    } 
}

foreach($contents2 as $id2 => $c2) {
    if (get_class($c2) != "B") { 
        (unset) $contents2[$id2]; 
    } 
}

foreach($contents3 as $id3 => $c3) {
    if (get_class($c2) != "C") { 
        (string) $contents3[$id3]; 
    } 
}

foreach($contents4 as $id4 => $c4) {
    if (get_class($c4) != "C") { 
        $contents4[$id4] = 2; 
    } 
}

?>
