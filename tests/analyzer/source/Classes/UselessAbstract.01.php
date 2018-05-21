<?php

abstract class abstractClass {
    abstract function ac();
}

class abstractSubClass extends abstractClass {
    function ac() { $a++; }
}

abstract class uselessAbstractClass {
    function ac() { $a++; }
}

class normalClasssWithoutExtends {
    function ac() { $a++; }
}

?>