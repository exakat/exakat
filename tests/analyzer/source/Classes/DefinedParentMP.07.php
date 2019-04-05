<?php

namespace A\B;

class C extends \RuntimeException {
    public function __construct($code = 0, \Exception $previous = null) {
        parent::__construct($ok);
    }
}

// Not an exception
class C2 extends RuntimeException {
    public function __construct($code = 0, \Exception $previous = null) {
        parent::__construct($ko);
    }
}

?>