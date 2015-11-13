<?php

class xThrowAndCatch {
    public function __toString() {
        try {
            throw new Exception();
        } catch (\Exception $e) {
        
        }
    }
}

class xThrow {
    public function __toString() {
        try {
        } catch (\Exception $e) {
        
        }
        throw new Exception();
    }
}

function y() {
    throw new Exception();
}

?>