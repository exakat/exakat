<?php

interface i {
    function fooI();
}

abstract class z { 
    abstract function foox();
             function fooB() { return 3;}
}

abstract class y extends z {

}

class x extends y implements i {
    function fooI(){}
    function fooX(){}
    function fooA(){}
    function fooB(){}
}
?>