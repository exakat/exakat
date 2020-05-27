<?php

class a {
    public $b = 1;
    protected $c;
    private $d = 1, $e, $e2;
    protected $f, $f2, $g;
    
    function __construct() {
        $this->b = 1;
        $this->c = 2;
        $this->d = 3;
        $this->e = 4 + $THIS->f = 3;
        $this->e2 = 4 + $this->f = 3;
        $this->f2 = 4 + $a * 3;
    }
}
?>