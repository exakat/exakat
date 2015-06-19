<?php

class a { public $cpa1; }

//trait t { public $tca1; }

class b extends a { public $cpb1 = 1; }

class c extends b { public $cpc1 = 1; }

class d extends c { 
    public $cpd1 = 1; 
    
    public function x() {
        $this->cpa1;
        $this->cpb1;
        $this->cpc1;
        $this->cpd1;
        $this->cpe1;
    }
}

?>