<?php

class staticToString {
    static public function __toString() {}
}

class protectedToString {
    protected function __toString() {}
}

class privateToString {
    private function __toString() {}
}

class defaultToString {
    function __toString() {}
}

class publicToString {
    public function __toString() {}
}

class staticNonPublicToString {
    static protected function __toString() {}
}


function __toString() {}

?>