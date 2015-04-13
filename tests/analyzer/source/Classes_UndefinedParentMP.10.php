<?php

class x extends unavailableParentM { 
    function a() {
        parent::y();
    }
}

class x2 extends unavailableParentP { 
    protected $zo = 1;
    private   $zi = 1;
    public    $zu = 1;
    
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