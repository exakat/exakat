<?php

interface i {
    function methodINone() ;
    function methodIPublic() ;
    function methodIPrivate() ;
    function methodIProtected() ;
}

class X implements i {
    function methodINone() {}
    public function methodIPublic() {}
    private function methodIPrivate() {}
    protected function methodIProtected() {}
}

new X;

?>