<?php

class x {
    public function __toString() {
        throw new Exception();
//        y();
    }
}

class x2 {
    public function __toSTRING() {
        try {
            throw new Exception();
        } catch (Exception $e) {}
    }
}

class x3 {
    public function __TOString() {
    }
}

function y() {
    throw new Exception();
}

?>