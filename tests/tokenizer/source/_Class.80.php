<?php

class a {
    protected $aprotected = 1;
    
    function foo() {
        $this->aprotected = 2;
    }
}



class b extends a {
    protected $aprotected = 4;
    
    function foo() {
        $this->aprotected = 2;
    }
}




class c extends b {
    protected $aprotected = 4;
    
    function foo() {
        $this->aprotected = 2;
    }
}




class d extends b {
    protected $aprotected = 4;
    
    function foo() {
        $this->aprotected = 2;
    }
}


?>