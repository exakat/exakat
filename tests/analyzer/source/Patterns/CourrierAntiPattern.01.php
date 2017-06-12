<?php

class A 
{
    protected $b;
    protected $c;

    public function __construct(B $b,C $c, $d) {
        $this->b = $b;
        $this->c = $c;
    }

    public function d($e) {
        $o = E::find($this->c);

        (new E($this->b))->e($e);
    }
}

?>