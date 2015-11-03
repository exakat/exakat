<?php

class a { public $cpa1, $cpa2; }

interface b {  }

trait c1 { public $cpc11 = 1; }
trait c2 { public $cpc21 = 1; }

class d extends a implements b { 
    use c1, c2;
    public $cpd1 = 1; 
    
    public function x() {
        $this->cpa1;
        $this->cpa2;
        $this->cpc11;
        $this->cpc21;
        $this->cpd1;
        $this->cpe1;
    }
}

?>