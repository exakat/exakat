<?php

interface i1 {
    function i1() ;
}

interface i2 extends i1 {
    function i2() ;
}

class ai0 implements i2 {
}
class ai1 implements i2 {
    function i1() {}
}
class ai2 implements i2 {
    function i2() {}
}
class ai4 implements i2 {
    function i1() {}
    function i2() {}
}

?>