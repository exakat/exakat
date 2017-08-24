<?php

class Foo {
    public function render() {
        $a = $this->getLocalMethod();
        $a = $this->getLocalStaticMethod();
        $b = $b->getExternalMethod();
    }

    public function getLocalMethod()
    {
        return 2;
    }

    static public function getLocalStaticMethod()
    {
        return 2;
    }

    public function getExternalMethod()
    {
        return 1;
    }
}

class Foo2 {
    static public function getExternalMethod()
    {
        return 3;
    }
}

?>