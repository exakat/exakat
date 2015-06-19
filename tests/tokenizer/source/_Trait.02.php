<?php
class B {
    public function C() {
        echo 'D';
    }
}

trait E {
    public function F() {
        G::H();
        echo 'I';
    }
}

class J extends K {
    use L;
}

$a = new M();
$b->N();
?>