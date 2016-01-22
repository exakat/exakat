<?php

class a {
    function ax() {}
    function aa() {}
    function ab() {}
    function ac() {}
    function ad1() {}
    function ad2() {}

    const A = 1, AB = 2, AC = 3, AD1=4, AD2 = 5;
}

class b extends a {
    const B = 1, AB = 2;
    function bx() {}
    function ab() {}
    function ac() {}
    function bc() {}
}

class c extends b {
    const C = 1, AC = 3;

    function cx() {}
    function ac() {}
    function bc() {}
}

class ad1 extends c {
    const C = 1, AD1 = 3;
    function ad1x() {}
    function ad1() {}
}

class ad2 extends c {
    const C = 1, AD1 = 3;
    function ad2x() {}
    function ad2() {}
}