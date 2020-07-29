<?php

interface i {
    function i1 () ;
    function i2 () ;
    function i3 () ;
}

class OK implements i {
    function i1 () {}
    function i2 () {}
    function i3 () {}
    function ok1 () {}
    function ok2 () {}
    function ok3 () {}
}

class KO0 implements i {
    function ko1 () {}
    function ko2 () {}
    function ko3 () {}
}

class KO1 implements i {
    function i1 () {}
    function ko1 () {}
    function ko2 () {}
    function ko3 () {}
}

class KO2 implements i {
    function i2 () {}
    function i3 () {}
    function ko1 () {}
    function ko2 () {}
    function ko3 () {}
}

?>