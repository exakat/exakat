<?php

abstract class uselessAbstractClass {
    function a() {}
}

abstract class abstractEmptyClass {}

abstract class usedAbstractClass {
    function a() {}
}

abstract class subUsedAbstractClass extends usedAbstractClass {
    function a() {}
}

abstract class subSubUsedAbstractClass extends subUsedAbstractClass {
    function a() {}
}

class subSubSubUsedAbstractClass extends subSubUsedAbstractClass {
    function a() {}
}

?>