<?php

        // OK ($x and $y !)
        preg_match('/PHP1/', $string, $r);
        $a = $r[1];

        // OK ($x and $y !)
        preg_match('/PHP2/', $string, $x);
        $a = $y[1];

        // KO ($z is used immediately)
        preg_match('/PHP3/', $string, $z);
        $a = $z[1][2];

        // OK (existence is tested)
        if (preg_match('/PHP4/', $string, $z)) {
            $a = $z[1];
        }

?>