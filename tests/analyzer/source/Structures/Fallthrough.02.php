<?php

switch ($noBreakthrough) {
    case A:
        ++$a;
        break;
    case B:
        ++$a;
        break;
    case C:
        ++$a;
        break;
    default:
        ++$d;
}

switch ($withBreakthrough) {
    case A:
        ++$a;
        break;
    case B:
        ++$a;
    case C:
        ++$a;
        break;
    default:
        ++$d;
}

?>