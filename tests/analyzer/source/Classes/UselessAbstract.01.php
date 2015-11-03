<?php

abstract class abstractClass {
    function ac() { $a++; }
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