<?php

$or = 1;

function null () {}

class truish {
    function __call($name, $args) {
        print "$name\n";
    }

    function x() {
        define('OR', 1);
        $this->die();
        $this->exit();
    }
}
?>