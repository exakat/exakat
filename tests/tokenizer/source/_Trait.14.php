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
        self::foo();

        self::talias();
        self::talias2();

        self::foot();
        self::foot2();
    }

}
