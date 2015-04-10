<?php


    function B($a) {

        if (C($a)) {
            $this->D($a);
        } elseif ($a instanceof E) {
            $a->F($this);
        } elseif (G($a)) {
                    } elseif (H($a)) {

            I($a);
            foreach ($a as $b => $c) {

                if (J($b)) {

                                        if (!H($c) || !L('M', $c) || !L('O', $c)) {
                        throw new P('Q');
                    }

                    $d = isset($c['R']) ? $c['R'] : [];
                    $b = $c['M'];
                    $c = $c['O'];

                } elseif (H($c) && L('O', $c)) {

                                        $d = isset($c['R']) ? $c['R'] : [];
                    $c = $c['O'];

                } else {
                                                                                $d = [];
                }

                $this->AB($b);
                $this->AC($d);
                $this->B($c);
                $this->AE();

            }

        } elseif (AF($a)) {

            throw new P('AH' . AI($a));

        }

    }

?>