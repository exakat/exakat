<?php
function foo($phpversion) {
        // version range 1.2.3-4.5.6
        foo2($x, $y);
        echo $x.$y;
        if (strpos($this->phpVersion, '-') !== false) {
            list($lower, $upper) = explode('-', $phpversion);
            return version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0;
        }
}

function foo2(&$a, $b) {
    $a = 1; $b = 3;
}


?>