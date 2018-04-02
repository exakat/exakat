<?php

// class constante used outside the class 

use a as b;

class a {
    protected const APROTECTED = 4,  APROTECTED2 = 5, APROTECTEBUTNOTUSEBELOW = 8;
}

class c extends a {
    private const PRIVATECONSTANT = a::APROTECTED;
    public $publicProperty = a::APROTECTED2;
}

?>