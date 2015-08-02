<?php
    function B () {
        while (C) {
            $a = yield;
            if ($a === null) {
                break;
            }
            if (isset($this->D[$b])) {
                $c = $this->D[$b];
                unset($this->D[$b]);
                G($c->H);
                $d = 'I';
                if (isset($this->J[$b])) {
                    $d = $this->J[$b];
                    unset($this->J[$b]);
                }
                $c->M->N($d . $a);
            }
            else {
                if (isset($this->J[$b])) {
                    $this->J[$b] .= $a;
                }
                else {
                    $this->J[$b] = $a;
                }
            }
        }
    }