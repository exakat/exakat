<?php

interface j {
    function methodINone() ;
    function methodIPublic() ;
    function methodIPrivate() ;
    function methodIProtected() ;
}

interface i extends j {}

class X implements i {
    function methodINone() {}
    public function methodIPublic() {}
    private function methodIPrivate() {}
    protected function methodIProtected() {}
}

new X;

?>