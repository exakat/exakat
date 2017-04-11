<?php

class A {
    function intactMethodA() {}
    function overwrittenMethodInAA() {}
    function overwrittenMethodInABAC() {}
    function OVERWRITTENMethodInAD() {}
}

class AA extends A {
    function intactMethodAA() {}
    function overwrittenMethodInAA() {}
}

// Multiple overwriting
class AB extends A {
    function intactMethodAB() {}
    function overwrittenMethodInABAC() {}
}

class AC extends A {
    function intactMethodAC() {}
    function overwrittenMethodInABAC() {}
}

// overwriting with case change
class AD extends A {
    function intactMethodAD() {}
    function OVERWRITTENMethodInAD() {}
}

?>
