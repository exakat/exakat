<?php
class MyClass {
    public function __destruct() {
        echo "Destroying object!\n";
    }
}

$o1 = new MyClass;

$r1 = new Weakref($o1);

if ($r1->valid()) {
    echo "Object still exists!\n";
    var_dump($r1->get());
} else {
    echo "Object is dead!\n";
}

unset($o1);

if ($r1->valid()) {
    echo "Object still exists!\n";
    var_dump($r1->get());
} else {
    echo "Object is dead!\n";
}
?>