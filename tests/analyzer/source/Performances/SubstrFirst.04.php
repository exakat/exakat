<?php
            $fnp1 = mb_strtolower($name->code1);

            if (($offset = strpos($fnp1, '\\')) === false) {
                $prefix = $fnp1;
            } else {
                $prefix = substr($fnp1, 0, $offset);
            }
            
            $fnp = mb_strtolower($name->code2);
            $prefix = substr($fnp, 0, $offset);

            $fnp2 = substr($source3, 0, $offset);
            $fnp2 = mb_strtolower($fnp2);

?>