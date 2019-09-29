<?php

class a {
    public $b = 1;
    protected $c;
    private $d = 1, $e;
    protected $f, $g;
    
    function __construct() {
        $this->b = 1;
        $this->c = 2;
        $this->d = 3;
        $this->e = 4 + $THIS->f = 3;
        $this->f = 4 + $a * 3;
    }
}
?>