<?php

class useThis {
    function nothing() {
        $THIS = 2;
    }

    static public function getCalledClass() {
        return get_called_class();
    }

    static public function getCalledClassWithArg($a) {
        return get_object_vars($a);
    }

    static public function getCalledClassWithThis() {
        return get_class($this);
    }

    public function getCalledClassWithThis2() {
        return get_class_methods($this);
    }

    function __destruct() {
        // Can't find in magic methods
        return get_parent_class();
    }
}
?>