<?php


        foreach($this->B as &$a) {
            if (C($a) && empty($a[0])) {
                unset($a);
             }
        }

        foreach($this->B as $b) {
            if (C($a) && empty($b[0])) {
                unset($b);
             }
        }

        foreach($this->B as $c) {
            if (C($a) && empty($c[0])) {
                unset($c[0]);
             }
        }

        foreach($this->B as &$d) {
            if (C($a) && empty($d[0])) {
                unset($d[0]); // OK (will destroy something)
             }
        }


?>