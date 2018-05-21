<?php

abstract class abstractClass {
    const X = 1;
}

class abstractSubClass extends abstractClass {
}

abstract class abstractClassTrait {
    use T;
}

class abstractSubClass extends abstractClassTrait {
}

abstract class uselessAbstractClass {
}

class normalClasssWithoutExtends {
}

?>