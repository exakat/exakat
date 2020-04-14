<?php

class a {
    private $a;
    protected $o = array();
    public $b;
}

class b extends a {

}

class c extends b {
    
    function foo() {
        $this->a['a'] = 3;
        $this->b['a'] = 3;
        $this->c[] = 3;
        $this->o = 1;
        $this->o2 = 1;
        
        print_r($this);
    }
}

$c = new c();
$c->foo();
?>