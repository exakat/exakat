<?php

class x {
    function __construct() {}
    
    public function foo() {
        return;
    }
    
    protected function foo1() {
        ++$a;
    }
    
    static function foo2() {
        if (++$a) {
            return ;
        } else {
            ++$a;
        }
    }
    
    protected function foo3() {
        if (++$a) {
            return 1;
        } else {
            return ;
        }
        
        return ;
    }
}

?>