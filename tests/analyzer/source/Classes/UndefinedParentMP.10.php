<?php

class x extends unavailableParentM { 
    function a() {
        parent::y();
    }
}

class x2 extends unavailableParentP { 
    protected static $zo = 1;
    protected static $zi = 1;
    public    static $zu = 1;
    
    function a() {
        parent::$y;
    }
}

class x3 extends x2 { 
    function a() {
        parent::$zu;
        parent::$zo;
        parent::$zi;
//        parent::z();
    }
}

?>