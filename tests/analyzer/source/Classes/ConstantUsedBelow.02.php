<?php

// class constante used outside the class 

use a as b;

class a {
    protected const APROTECTED = 4, APROTECTEBUTNOTUSEBELOW = 8;
}

class c extends a {
    function cd($a = a::APROTECTED) {
    }
}

?>