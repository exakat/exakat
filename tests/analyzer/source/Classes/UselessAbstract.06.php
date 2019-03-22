<?php

abstract class abstractClass {
    abstract function ac() ;
}

class abstractSubClass extends abstractClass {
    function ac() { $a++; }
}

abstract class abstractClass2 {
    abstract function ac();
}

class abstractSubClass2 extends abstractClass2 {
    function ac() { $a++; }
}

abstract class abstractClass3 {
    abstract function ac1();
             function ac2() {}
}

class abstractSubClass3 extends abstractClass3 {
    function ac1() { $a++; }
    function ac2() { $a++; }
}

abstract class abstractClass4 {
             function ac1() {}
             function ac2() {}
}

class abstractSubClass4 extends abstractClass4 {
    function ac1() { $a++; }
    function ac2() { $a++; }
}

abstract class abstractClass5 {
    abstract function ac1();
    abstract function ac2();
}

class abstractSubClass5 extends abstractClass5 {
    function ac1() { $a++; }
    function ac2() { $a++; }
}

abstract class abstractClass7 {
             function ac1() {}
             function ac2() {}
}



?>