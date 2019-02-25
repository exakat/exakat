<?php

class x {
    private $c = null;
    
    function foo($a) {
        static $s;
        global $g;

        try{
            $this->foo2();
            $a->b();
            $this->c->d();
            
            $f = new x();
            $f->g();
            
            $this->b()->d();
            
            $g->h();
            $i->h();
            
            $GLOBALS['a']->foo();
        } catch(Exception $e) {
            $e->getMessage();
        }
    }
    
    function foo2(){}
}

?>