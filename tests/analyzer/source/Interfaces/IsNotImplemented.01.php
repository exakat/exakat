<?php

interface i1 {
    function i1() ;
}

class ai1 implements i1 {}
class ai2 implements i1 {
    function i1() {}
}
abstract class ai3 implements i1 {
    function i2() {}
}
abstract class ai4 implements i1 {
    abstract function i1();
}

class ai5 extends ai4 { 
}

new ai5();

?>