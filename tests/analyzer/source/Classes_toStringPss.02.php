<?php

class staticisset {
    static public function __isset($a) {}
}

class protectedisset {
    protected function __isset($a) {}
}

class privateisset {
    private function __isset($a) {}
}

class defaultisset {
    function __isset($a) {}
}

class publicisset {
    public function __isset($a) {}
}

class staticNonPublicisset {
    static protected function __isset($a) {}
}


function __isset($a) {}

?>