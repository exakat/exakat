<?php

trait A {
    function C (){}
}

trait B {
    function C (){}
}

class Talker {
    use A, B {
        B::C insteadof A;
        B::D insteadof A;
    }
}
?>