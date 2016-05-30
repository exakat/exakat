<?php

class Foo {

    // Don't use is_object
    public function bar($o) {
        if (!is_object($o)) { return false; } // Classic argument check
        return $o->method();
    }

    // use instanceof
    public function bar($o) {
        if ($o instanceof myClass) {  // Now, we know which methods are available
            return $o->method();
        }
        
        return false; } // Default behavior
    }

    // use of typehinting
    // in case $o is not of the right type, exception is raised automatically
    public function bar(myClass $o) {
        return $o->method();
    }
}

?>