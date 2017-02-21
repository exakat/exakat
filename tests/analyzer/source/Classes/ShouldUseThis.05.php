<?php

abstract class useThis {
    abstract function abstractClassMethod();

    abstract static function abstractClassStaticMethod();

    static function ClassStaticMethodWithBody() {
        return 1;
    }

    public function ClassMethodWithBody() {
        return 2;
    }
}

?>