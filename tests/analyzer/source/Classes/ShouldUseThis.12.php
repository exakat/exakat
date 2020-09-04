<?php

class useThis {
    function withThis() {
        $this->p = 2;
    }

    public function getCalledClass() {
        return get_called_class();
    }

    static public function getCalledClassWithArg($a) {
        return get_class_methods($a);
    }

    static public function getCalledClassWithoutThis() {
        return get_class();
    }

    public function getClassMethods() {
        return get_class_methods();
    }

    function __destruct() {
        // Can't find in magic methods
        return get_parent_class();
    }
}
?>