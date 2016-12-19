<?php

interface i1 {
    function i1() ;
}

class aOK implements i1 {
    function i1 () {}
}

class aKO1 implements i1 {
    function i1 () {}
    function a1 () {}
}

class aKO2 {
    function i1 () {}
    function a1 () {}
}

class aKO3 extends aKO2 {
    function i1 () {}
    function a1 () {}
}

class aKO4 extends aKO2 implements i1 {
    function i1 () {}
    function a1 () {}
}
