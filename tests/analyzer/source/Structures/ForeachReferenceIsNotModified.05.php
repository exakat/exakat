<?php

        foreach($s1 as &$a1) {
            // modified here
            foreach($a1 as $b => &$c) {
                $c++;
            }
        }

        foreach($s2 as $k2 => &$a2) {
            // not modified
            foreach($a2 as $b => $c2) {
                $c2++;
            }
        }

?>