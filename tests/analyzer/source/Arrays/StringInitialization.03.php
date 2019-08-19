<?php

        // Query the Media table for comment media.
        if (is_numeric($a)) {
            $a[] = $a;
        }

        if (($b = is_scalar($a)) === FALSE) {
            $a[] = $a;
        }

        if (is_real($a) === true) {
            $a[] = $a;
        }
        
        if (true == is_string($a)) {
            $a[] = $a;
        }

        if (is_array($a)) {
            $a[] = $a;
        }
        
print_r($a);
?>