<?php

class Foo {
    private function Bar($a, $b) {
        return $a + $b;
    }

    private function BarEllipsis($a, ...$b) {
        return $a + $b;
    }

    private function BarFunc_get_arg($a, $b) {
        $c = func_get_args();
        return $a + $b + $c;
    }
    
    public function foobar() {
        $this->Bar(1);
        // Good amount
        $this->Bar(1, 2);
        // Too Many
        $this->Bar(1, 2, 3);

        $this->BarEllipsis(1);
        $this->BarEllipsis(1, 2);
        $this->BarEllipsis(1, 2, 3);

        $this->BarFunc_get_arg(1);
        $this->BarFunc_get_arg(1, 2);
        $this->BarFunc_get_arg(1, 2, 3);

    }
}


?>