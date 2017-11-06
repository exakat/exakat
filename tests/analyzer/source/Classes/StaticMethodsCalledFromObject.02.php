<?php

class foo {
    // No static are found
    function b() {
        $d = $this->c($d, $e);
        $d = $this->d($d, $e);
        $d = $this->e($d, $e);
    }
    
    protected function c($a, $b) {}
    protected static function d($a, $b) {}
    static protected function e($a, $b) {}
}

?>