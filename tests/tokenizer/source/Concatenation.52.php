<?php

        foreach (B::$a AS $b => $c) {
            if (C($d, $b) === 0) {
                $e = D('E', F, $d) . 'G';
                if ($c === null) {
                    if ($f = H($e)) {
                        require $f;
                        return I;
                    }
                } else {
                    foreach((array)$c AS $g) {
                        if (J($g . F . $e)) {
                            require $g . F . $e;
                            return I;
                        }
                    }
                }
            }
        }