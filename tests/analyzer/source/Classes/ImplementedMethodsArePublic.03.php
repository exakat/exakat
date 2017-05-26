<?php

interface k {
    function methodINone() ;
    function methodIPublic() ;
    function methodIPrivate() ;
}

interface j extends k {
    function methodIProtected() ;
}
interface i extends j {}

class X implements i {
    function methodNonImplemented() {}
    function methodINone() {}
    public function methodIPublic() {}
    private function methodIPrivate() {}
    protected function methodIProtected() {}
    
}

new X;

?>