<?php

abstract class a {
             function ax() {}
    abstract function aa();
    abstract function ab();
    abstract function ac();
    abstract function ad1();
    abstract function ad2();

    const A = 1, AB = 2, AC = 3, AD1=4, AD2 = 5;
}

abstract class b extends a {
    const B = 1, AB = 2;
    function bx() {}
    function ab() {}
    function ac() {}
    function bc() {}
}

class c extends b {
    const C = 1, AC = 3;

    function aa() {}
    function ad1() {}
    function ad2() {}

    function cx() {}
    function AC() {}
    function BC() {}
}

class aad1 extends c {
    const C = 1, AD1 = 3;
    function ad1x() {}
    function ad1() {}
}

class aad2 extends c {
    const C = 1, AD1 = 3;
    function ad2x() {}
    function ad2() {}
}