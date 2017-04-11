<?php

class A {
    function overwrittenMethodInAA() { return 1;}
    function overwrittenMethodInAA2() { return 1;}
    function NotOverwrittenMethod() { return 2;}
}

class AA extends A {
    function overwrittenMethodInAA() { return $this->p;}
    function overwrittenMethodInAA2() { return 1;}
    function LocalMethodInAA() { return 1;}
}

?>
