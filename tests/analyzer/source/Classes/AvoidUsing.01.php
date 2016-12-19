<?php

namespace {
    new AvoidThisClass();

    AvoidThisClass::constante + 1;

    if ($a instanceof AvoidThisClass) {
        AvoidThisClass::$yes = AvoidThisClass::methodCall();
    }

    class_alias('AvoidThisClass', $b);
    class_alias('\NS\AvoidThisClass', $b);
    class_alias('NS\AvoidThisClass', $b);
}

namespace NS {
    new AvoidThisClass();
    
    AvoidThisClass::constante + 1;
    
    if ($a instanceof AvoidThisClass) {
        AvoidThisClass::$yes = AvoidThisClass::methodCall();
    }
    
    function x (AvoidThisClass $a) {}
    
    class y extends AvoidThisClass implements AvoidThisClass {}

}

namespace NS2 {

    use NS\AvoidThisClass as b;
    
    new AvoidThisClass();
    
    AvoidThisClass::constante + 1;
    
    if ($a instanceof AvoidThisClass) {
        AvoidThisClass::$yes = AvoidThisClass::methodCall();
    }
    
    function x (AvoidThisClass $a) {}
    
    class y extends AvoidThisClass implements AvoidThisClass {}
    
    // Function call, with the name of the class. Should not be spotted.
    AvoidThisClass(2, 3, 4);
}



?>