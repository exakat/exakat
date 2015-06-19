<?php

interface i {
    function interfaceIMethod( $i) ;
}

class x implements \ARRAYACCESS, i {
    public function offsetSet($offset, $value) {}
    public function offsetExists($offset) {}
    public function offsetUnset($offset) {}
    public function offsetGet($offset) {}
    
    public function unusedMethodx() {}

    public function interfaceIMethod($ix) {}
}

class xx extends x {
    public function offsetSet($offsetxx, $value) {}
    public function offsetExists($offsetxx) {}
    public function offsetUnset($offsetxx) {}
    public function offsetGet($offsetxx) {}
    
    public function unusedMethodxx() {}

    public function interfaceIMethod($ixx) {}
}


class y {
    public function offsetSet($offsety, $valuey) {}
    public function offsetExists($offsety) {}
    public function offsetUnset($offsety) {}
    public function offsetGet($offsety) {}
    
    public function unusedMethody() {}

    public function interfaceIMethod($iy) {}
}

?>