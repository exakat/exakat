<?php

namespace A\B {
    class C {
        static function d(){}
        static function f(){return true;}
    }
}

namespace {
    use \A\B\C as E;

    spl_autoload_register('\A\B\C::d');

    spl_autoload_register(\A\B\C:: class.'::d');
    spl_autoload_register(E:: class.'::d');
    spl_autoload_register(E:: class.'::F');

}

?>