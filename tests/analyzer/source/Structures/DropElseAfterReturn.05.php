<?php
    function foo() {

        if (is_array($theme)) {
            $b++;
        } elseif ($b == 1 ) {
            $a++;
        } else {
            return array();
        }
        
        if (is_array($theme)) {
            $b++;
        } else if ($b == 2) {
            $a++;
        } else {
            return array();
        }

        if (is_array($theme)) {
            $b++;
        } else {
            $a++;
            if ($b == 3) {
                $a++;
            } else {
                return array();
            }
        }

        if ($b == 4) {
            $a++;
        } else {
            return array();
        }
        
    }
?>