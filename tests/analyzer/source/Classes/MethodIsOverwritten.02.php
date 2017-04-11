<?php

class A {
    function intactMethodA() {}
    function overwrittenMethodInAA() {}
    function overwrittenMethodInABAC() {}
    function OVERWRITTENMethodInAD() {}
}

class B extends A {}
class D extends A {}

class AA extends B {
    function intactMethodAA() {}
    function overwrittenMethodInAA() {}
}

// Multiple overwriting
class AB extends B {
    function intactMethodAB() {}
    function overwrittenMethodInABAC() {}
}

class AC extends B {
    function intactMethodAC() {}
    function overwrittenMethodInABAC() {}
}

// overwriting with case change
class AD extends D {
    function intactMethodAD() {}
    function OVERWRITTENMethodInAD() {}
}

?>
