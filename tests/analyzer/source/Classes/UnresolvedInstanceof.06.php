<?php

class XnoParent {
    function f() {
        $a1 instanceof self;
        
        $b1 instanceof static;
        
        $c1 instanceof parent;
    }
}

class XParent extends XnoPrant {
    function f() {
        $a2 instanceof self;
        
        $b2 instanceof static;
        
        $c2 instanceof parent;
    }
}

interface i {}

class XnoParent2 implements i {
    function f() {
        $a3 instanceof self;
        
        $b3 instanceof static;
        
        $c3 instanceof parent;
    }
}