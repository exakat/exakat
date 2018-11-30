<?php

trait TraitB {
    function catch() {}
}

trait TraitA {
    function catch() {}
}

class Foo
{
    use TraitA, TraitB {
        TraitA
            ::
            catch insteadof namespace\TraitB;
        TraitA::list as public foreach;
        TraitB::throw as public;
        TraitB::self as public;
    }

    use TraitC {
        try as public attempt;
        exit as die;
        \TraitC::exit as bye;
        namespace\TraitC::exit as byebye;
        TraitC
            ::
            exit as farewell;
    }
    
    function bar() {
        $this->catch();
    }
}
