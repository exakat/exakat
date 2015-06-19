<?php

interface i {
    function interfaceIMethod() ;
}

class x implements \Arrayaccess, i {
    public function offsetset($offset, $value) {}
    public function offsetexists($offset) {}
    public function offsetunset($offset) {}
    public function offsetget($offset) {}
    
    public function unusedmethodx() {}

    public function interfaceimethod() {}
}

class xx extends x {
    PUBLIC FUNCTION OFFSETSET($OFFSETXX, $VALUE) {}
    PUBLIC FUNCTION OFFSETEXISTS($OFFSETXX) {}
    PUBLIC FUNCTION OFFSETUNSET($OFFSETXX) {}
    PUBLIC FUNCTION OFFSETGET($OFFSETXX) {}
    
    PUBLIC FUNCTION UNUSEDMETHODXX() {}

    PUBLIC FUNCTION INTERFACEIMETHOD() {}
}


class y {
    Public Function Offsetset($Offsety, $Valuey) {}
    Public Function Offsetexists($Offsety) {}
    Public Function Offsetunset($Offsety) {}
    Public Function Offsetget($Offsety) {}
    
    Public Function Unusedmethody() {}

    Public Function Interfaceimethod() {}
}

?>