<?php

class useSelfPrivate {
    private function foo1() {}

    public function bar() {
        $x = 'foo1';
        return $this->$x();
    }
}

class useSelfPrivate2 {
    private static function foo1() {}

    public function bar() {
        $x = 'foo1';
        return self::$x();
    }
}

class dontUseSelf {
    private function foo2() {}

    public function bar() {
        return $this->bar();
    }
}

class dontUseSelf2 {
    private static function foo2() {}

    public function bar() {
        return self::bar();
    }
}

?>