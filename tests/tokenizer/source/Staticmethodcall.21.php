<?php

class x {
    static function foo() {
        return $this;
    }

    static function foo2() {
        return $this;
    }

    static function foo3() {
        return $this;
    }

    static function foo4() {
        return $this;
    }

    static function foo5() {
        return $this;
    }
}

X::foo()
  ::foo2()
  ::foo3()
  ::foo4()
  ::foo5();
?>