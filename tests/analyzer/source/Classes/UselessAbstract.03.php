<?php

abstract class uselessAbstractClass {
    function a() {}
}

abstract class abstractEmptyClass {}

abstract class usedAbstractClass {
    abstract function a();
}

abstract class subUsedAbstractClass extends usedAbstractClass {
    abstract function a();
}

abstract class subSubUsedAbstractClass extends subUsedAbstractClass {
    abstract function a();
}

class subSubSubUsedAbstractClass extends subSubUsedAbstractClass {
    function a() {}
}

?>