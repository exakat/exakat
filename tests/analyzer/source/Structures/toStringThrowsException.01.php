<?php

class x {
    public function __toString() {
        throw new Exception();
//        y();
    }
}

function y() {
    throw new Exception();
}

?>