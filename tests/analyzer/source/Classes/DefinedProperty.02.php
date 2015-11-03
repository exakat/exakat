<?php

class a { public $cpa1, $cpa2; }

//trait t { public $tca1; }

class b implements a { public $cpb1 = 1; 
                       public $cpb2 = 2; 
                        }

class c1 implements b { public $cpc11 = 1; }
class c2 implements b { public $cpc21 = 1; }

class d implements c1, c2 { 
    public $cpd1 = 1; 
    
    public function x() {
        $this->cpa1;
        $this->cpa2;
        $this->cpb1;
        $this->cpb2;
        $this->cpc11;
        $this->cpc21;
        $this->cpd1;
        $this->cpe1;
    }
}

?>