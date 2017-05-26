<?php

interface k {
    function methodKNone() ;
    function methodKPublic() ;
    function methodKPrivate() ;
    function methodKProtected() ;
}

interface j {
    function methodJNone() ;
    function methodJPublic() ;
    function methodJPrivate() ;
    function methodJProtected() ;
}

interface i {
    function methodINone() ;
    function methodIPublic() ;
    function methodIPrivate() ;
    function methodIProtected() ;
}

class X implements i,j,k {
    function methodNonImplemented() {}

    function methodINone() {}
    public function methodIPublic() {}
    private function methodIPrivate() {}
    protected function methodIProtected() {}

    function methodJNone() {}
    public function methodJPublic() {}
    private function methodJPrivate() {}
    protected function methodJProtected() {}

    function methodKNone() {}
    public function methodKPublic() {}
    private function methodKPrivate() {}
    protected function methodKProtected() {}
    
}

new X;

?>