<?php

    function D() {
        $this->E = F($this);
        $this->G = H('I', 'J', $this->E);
        $this->L($this->E);

        $this->N = $this->E;
        
        P::Q();
        
        $this->R = new S();
        $this->R->U($this->E);
        
        $this->W = new \X($this->E);
    }
    
    function Z($b) {
        $this->AA = $b;
    }
    
    function Q() {
        if (B::$c === null) {
            $d  = (AD(AE(AF(AF(__DIR__))), 'AH') !== AI);
            if ($d === AJ) {
                $e = 'AK'.AE(AF(AF(__DIR__))).'AO';
            } else {
                $e = AF(AF(AF(__FILE__))).'AO';
            }
            P::$c = new AV($e);
        }
    }
    
    function AW($f) {
                                                
        if (AD($f, 'I') !== AI) {
            if (BA($f, 1, 2) == 'BB') {
                $g = $f;
            } else {
                $g = 'BB'.$f;
            }
        } elseif (AD($f, 'BE') !== AI) {
            $g = 'BB'.H('BE', 'I', $f);
        } elseif (AD($f, 'BE') === AI) {
            P::Q();
            $h = P::$c->BQ($f);
            if (BR($h) == 1) {
                return AI;             } elseif (BR($h) == 3) {
                $g = $h[1];
            } else {
                                return AI;
            }
        } else {
            $g = $f;
        }
        
        if (BV($g)) {
            $i = new \BW($g);
            if ($g != $i->BX()) {
                                return AI;
            } else {
                return $g;
            }
        } else {
            return AI;
        }
    }
    
    function CA($f) {
        P::Q(); ;
        $j = P::$c->CE();
        $k = array();
        foreach($j as $l) {
            $m = CF($l, $f);

            if ($m < 4) {
                $k[] = $l;
            }
        }
        
        return $k;
    }
?>
