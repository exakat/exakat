<?php

        foreach($b as $c) {
            if (B($c)) {
                if ($c[1] === 2) {
                    $d += C($c[3], 'D');
                    continue;
                } else {
                    $d = $c[2];
                    $this->E[] = $c;
                }
            } else {
                $this->E[] = array(1 => G::H[$c],
                                        3 => $c,
                                        2 => $d);
            }
        }

        foreach($b as $c) {
            if (B($c)) {
                if ($c[1] === 3) {
                    $d += C($c[3], 'D');
                } else {
                    $d = $c[2];
                }
            } else {
                $this->E[] = array(1 => G::H[$c],
                                        3 => $c,
                                        2 => $d);
            }
        }

        foreach($b as $c) {
            if (B($c)) {
                if ($c[1] === 4) {
                    $d += C($c[3], 'D');
                } else {
                    $d = $c[2];
                    $d++;
                }
            } else {
                $this->E[] = array(1 => G::H[$c],
                                        3 => $c,
                                        2 => $d);
            }
        }

?>