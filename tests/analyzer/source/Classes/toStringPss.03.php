<?php

class staticCall {
    static public function __callstatic($a, $b) {}
}

class staticFinalCall {
    static public final function __callstatic($a, $b) {}
}

class justStaticCall {
    static function __callstatic($a, $b) {}
}

class protectedCall {
    protected function __callstatic($a, $b) {}
}

class privateCall {
    private function __callstatic($a, $b) {}
}

class defaultCall {
    function __callstatic($a, $b) {}
}

class publicCall {
    public function __callstatic($a, $b) {}
}

class staticNonPublicCall {
    static protected function __callstatic($a, $b) {}
}

class staticNonPublicCall2 {
    static private function __callstatic($a, $b) {}
}


function __callstatic($a, $b) {}

?>