<?php

// a is defined multiple times in other files
class b extends a {
    function foo() {
        a::inFamilya() ;
        a::inFamilyb() ;
        a::notDefined();
        c::notAClass();
    }
}