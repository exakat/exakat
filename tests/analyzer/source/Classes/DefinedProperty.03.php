<?php

trait a { public $cpa1, $cpa2; }

trait b { public $cpb1 = 1; 
          public $cpb2 = 2; }

trait c1 { use b; public $cpc11 = 1; }
trait c2 { use a; public $cpc21 = 1; }

class d { 
    use c1, c2;
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

$d = new d();
$d->x();

?>