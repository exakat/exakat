<?php

interface i {
    function interfaceIMethod( $i) ;
}

class x implements \Arrayaccess, i {
    public function offsetSet($offset, $value) {}
    public function offsetExists($offset) {}
    public function offsetUnset($offset) {}
    public function offsetGet($offset) {}
    
    public function unusedMethodx() {}

    public function interfaceIMethod($ix) {}
}

class xx extends x {
    public function offsetSet($offsetSetxx, $value) {}
    public function offsetExists($offsetExistsxx) {}
    public function offsetUnset($offsetUnsetxx) {}
    public function offsetGet($offsetGetxx) {}
    
    public function unusedMethodxx() {}

    public function interfaceIMethod($interfaceIMethodxx) {}
}

class xxx extends xx {
    public function offsetSet($offsetSetxxx, $value) {}
    public function offsetExists($offsetExistsxxx) {}
    public function offsetUnset($offsetUnsetxxx) {}
    public function offsetGet($offsetGetxxx) {}
    
    public function unusedMethodxxx() {}

    public function interfaceIMethod($interfaceIMethodxxx) {}
}

class xxxx extends xxx {
    public function offsetSet($offsetSetxxxx, $value) {}
    public function offsetExists($offsetExistsxxxx) {}
    public function offsetUnset($offsetUnsetxxxx) {}
    public function offsetGet($offsetGetxxxx) {}
    
    public function unusedMethodxxxx() {}

    public function interfaceIMethod($interfaceIMethodxxxx) {}
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