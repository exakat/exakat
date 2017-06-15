<?php

interface i {
    function i(); 
    function i2(); 
    function i3(); 
    function i4(); 
    function i5(); 
}

interface j extends i {}

// i is not implemented and declared
class foo {
    function i() {}
    function i2() {} 
    function i3() {} 
    function i4() {} 
    function i5() {} 
    function j() {}
}

// i is implemented and declared
class foo2 implements j {
    function i() {}
    function i2() {} 
    function i3() {} 
    function i4() {} 
    function i5() {} 
    function j() {}
    function k2() {} 
    function k3() {} 
    function k4() {} 
    function k5() {} 
    function k() {}
}

?>