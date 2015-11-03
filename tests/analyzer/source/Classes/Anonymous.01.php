<?php
var_dump(new class($i) {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class($i, $j) {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class {
    public function __construct($i) {
        $this->i = $i;
    }
});

class x {}

?>