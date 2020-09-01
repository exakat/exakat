<?php 

foreach($a as $b) {
    foreach($b as &$c) {
        $c = "(NULL, '" . $c . "', 0)";
    }
    foreach($b as $d) {
        $d = "(NULL, '" . $d . "', 0)";
    }
    $e = 'D' . implode(', ', $d);
}

?>