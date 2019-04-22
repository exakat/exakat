<?php

class x {
    private $xp = null;
    private $selfp = null;
    private $staticp = null;
    
 function a($b, ...$c) { $this->xp = new x();}
 function b2($b, Stdclass...$c2) {$this->selfp = new self;}
 function c ($d, $e): x  { $this->staticp = new static; }
 function d () { 
    $this->xp->a();
    $this->xp->a(5);
    $this->xp->a(5,6);
    $this->xp->c();
    $this->xp->c(5);
    $this->xp->c(5,6);

    $this->selfp->a();
    $this->selfp->a(5);
    $this->selfp->a(5,6);
    $this->selfp->c();
    $this->selfp->c(5);
    $this->selfp->c(5,6);

    $this->staticp->a();
    $this->staticp->a(5);
    $this->staticp->a(5,6);
    $this->staticp->c();
    $this->staticp->c(5);
    $this->staticp->c(5,6);
    }
}

?>