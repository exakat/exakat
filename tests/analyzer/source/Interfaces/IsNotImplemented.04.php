<?php 
interface i1 {
    function i1() ;
}

class ai1 implements i1 {}
class ai2 implements i1 {
    function foo() {}
}
class ai3 implements i1 {
    function foo() {}
    function i1() {}
}
