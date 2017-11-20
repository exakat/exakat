<?php

    function fabc2() { }
    function fabc1() { }

class x {
    function abc() { }
    function abc2() { }
    static function sabc() { }
}

class x2 {
    function abc() { }
    function abc3() { }
    static function sabc() { }
}


abC();  // dummy 
sabC(); // dummy 
new aBc(); // dummy 
new saBc(); // dummy 

$x->ABC();
$x->abc();
$x->sabC();

x::Sabc();
x::sabc();

Fabc2();
fabc1();

?>