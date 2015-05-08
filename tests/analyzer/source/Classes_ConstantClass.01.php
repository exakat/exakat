<?php

class x2 {
    const a = 1;
    public $b = 2;
}

class x {
    const a = 1;
    const b = 2;
    const c = 3, d = 4;
}

interface i {
    const a = 1;
    const b = 2;
    const c = 3, d = 4;
    const a2 = 1;
    const b2 = 2;
    const c2 = 3, d2 = 4;
}

interface i2 {
    const a = 1;
    const b = 2;
    const c = 3, d = 4;
    const a2 = 1;
    const b2 = 2;
    const c2 = 3, d2 = 4;

    public function someMethod();
}

?>
