<?php
interface i {
    function __clone( ) ;
    function __destruct( ) ;
    function a() ;
}

class x implements i, i, i {
    public function __clone() {}
    public function __destruct() {}
    public function a() {}

    public function notInterfaceMethod() {}
}

?>