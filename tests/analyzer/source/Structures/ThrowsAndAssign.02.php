<?php

class x {
    private $h = null;

    function foo() {
        try {
            throw $this->h = new Exception('1');
            throw $e = new Exception('2');
            throw $g = new Exception('3');
        } catch (Throwable $f) {
            echo $e->getMessage();
        }
    }
}
?>