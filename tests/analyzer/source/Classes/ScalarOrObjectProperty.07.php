<?php

class x {
    protected $staticA;
    protected $staticB;
    protected $staticC;
    protected $staticD;

    protected $literalA = 2;
    protected $literalB = 2;
    protected $literalC = 2;
    protected $literalD = 2;

    public function b() {
//        $this->staticA::class;
        $this->staticB::constante;
        $this->staticC::methode();
        $this->staticD::$property;

// PHP 8.0
//        $this->literalA::class;
        $this->literalB::constante;
        $this->literalC::methode();
        $this->literalD::$property;
    }

}