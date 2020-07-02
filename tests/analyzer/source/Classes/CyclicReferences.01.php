<?php

class a {
    private $p = null;
    
    function foo() {
        $this->p->method($this);
        $this->p->method($a);
        $a->p->method($this);
    }
}

$a = new A();
$a->p->method($a);
$a->m()->method($a);
$a->p->method($b);

?>