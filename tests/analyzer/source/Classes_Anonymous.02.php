<?php
var_dump(new class($i) extends i {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class($i, $j) extends i {
    public function __construct($i) {
        $this->i = $i;
    }
});

var_dump(new class extends i {
    public function __construct($i) {
        $this->i = $i;
    }
});

class x {}

?>