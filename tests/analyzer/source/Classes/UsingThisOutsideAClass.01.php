<?php

$this->a;

function a () {
    $this->b;
}


$x = function ($this) {
    print $this;
};

$x = function ($this) {
};

class c {
    function d() {
        $this->e;
    }
}

trait t {
    function d() {
        $this->ef;
    }
}