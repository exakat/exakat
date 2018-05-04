<?php

        foreach ($a->b() as $lang) {
            if (!in_array($lang, $ret)) {
                $ret[] = $lang;
            }
        }

        foreach ($a->b() as $lang2) {
            if (!in_array($lang2, $ret2)) {
                $ret[] = $lang2;
            }
        }

?>