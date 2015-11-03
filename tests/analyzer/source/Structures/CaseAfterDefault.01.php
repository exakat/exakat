<?php
switch ($expr1) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    default:
        echo 'Default case';
        break;
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
}

switch ($expr2) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    default:
        echo 'Default case';
        break;
}

switch ($expr3) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    default:
        switch ($nestedExpr) {
    case 10:
        echo 'First case, with a break';
        break;
    case 11:
        echo 'Second case, which falls through';
        // no break
    default:
        echo 'Default case';
        break;
}
}

?>