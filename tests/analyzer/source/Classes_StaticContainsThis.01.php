<?php
class x {
    static function staticMethodWithThis() {
        $this->x + 1;
    }

    private function nonStaticMethodWithThis() {
        $this->x + 1;
    }
}

function y () {
    return $this;
}
?>