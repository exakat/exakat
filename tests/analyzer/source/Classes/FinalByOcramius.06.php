<?php

class aOK implements i1 {
    function i1 () {}
}

class aKO implements i2 {
    // i2 is supposed to come from i2, but we don't know i2 definition in this code
    function i2 () {}
}

final class FinalClass implements i {}

Abstract class abstractClass implements i {}

class normalClass implements i  {}

class normalClassNoImplements {}

interface i1 { 
    function i1 ();
}

?>