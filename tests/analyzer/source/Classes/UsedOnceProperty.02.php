<?php

class a {
    protected $aprotected1 =  10;
    protected $aprotected2 =  11;
    protected $aprotected3 =  12;
    protected $aprotected31 = 13;
    protected $aprotected32 = 14;
    
    function foo() {
        $this->aprotected1 = 2;
        $this->aprotected2 = 2;
        $this->aprotected3 = 2;
        $this->aprotected31 = 2;
        $this->aprotected31 = 2;
    }
}



class b extends a {
    protected $aprotected11 = 24;
    protected $aprotected2 =  20;
    protected $aprotected3 =  21;
    protected $aprotected31 = 22;
    protected $aprotected32 = 23;
    
    function foo() {
        $this->aprotected11 = 2;
        $this->aprotected2 = 2;
        $this->aprotected3 = 2;
        $this->aprotected3 = 2;
        $this->aprotected31 = 2;
        $this->aprotected32 = 2;
        $this->aprotected32 = 2;
        $this->aprotected32 = 2;
    }
}


?>