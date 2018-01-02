<?php
trait t {
    function foo() {
        $a = function () {
            echo $this;
        };
    }
}


trait t {
    function foo() {
        $this->a = function () {
            return 'b';
        };
    }
}

?>