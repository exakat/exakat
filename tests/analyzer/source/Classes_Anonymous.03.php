<?php
var_dump(new class($i) implements i {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class($i, $j) implements i {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class implements i {
    public function __construct($i) {
        $this->i = $i;
    }
});

class x {}

?>