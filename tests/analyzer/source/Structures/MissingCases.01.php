<?php
switch($a) {
    case 'a': doSomethingA(); break 1;
    case 'b': doSomethingB(); break 1;
    case 'c': doSomethingC(); break 1;
    case 'd': doSomethingD(); break 1;
    case 'e': doSomethingE(); break 1;
    default: doNothing();
}

// This switch operates on a, b, d and default 
switch($o->p) {
    case 'a': doSomethingA(); break 1;
    case 'b': doSomethingB(); break 1;

    case 'd': doSomethingD(); break 1;
    case 'e': doSomethingE(); break 1;
    default: doNothing();
}

// Not just strings
switch(C::$P1) {
    case 'a'.'3': doSomethingA(); break 1;
    case 'b': doSomethingB(); break 1;

    case 'd': doSomethingD(); break 1;
    default: doNothing();
}

// Not just strings
switch(C::$P2) {
    case 3 : doSomethingA(); break 1;
    case 'b': doSomethingB(); break 1;

    case 'd': doSomethingD(); break 1;
    default: doNothing();
}

// Very different set than the first one
switch(C::$P3) {
    case 'e' : doSomethingA(); break 1;
    case 'f': doSomethingB(); break 1;

    case 'd': doSomethingD(); break 1;
    default: doNothing();
}

?>