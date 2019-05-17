<?php

trait t2 {
    function foot2() {}
    function taliased2(){ echo __METHOD__;}
}

trait t {
    use t2;
    
    function foot() {}
    function taliased(){ echo __METHOD__;}
}

class x {
    use t {
        t::taliased as talias;
        t::taliased2 as talias2;
    }

    function foo(){ echo __METHOD__;}

    function test(){ 
        $this->foo();

        $this->talias();
        $this->talias2();

        $this->foot();
        $this->foot2();
    }

}
