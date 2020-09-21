<?php

        foreach($ini as &$f) {
            $f .= '()';
        }

        foreach($ini as $g) {
            $g .= '()';
        }

        foreach($ini as list($h, &$i)) {
            $h .= '()';
            $i .= '()';
        }


?>