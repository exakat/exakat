<?php

function x(Stdclass $a, $b, Stdclass $c) {
    return 1;
}

function x1(namespace\Stdclass $a, namespace\Stdclass $b, namespace\Stdclass $c = null) {

}

function x2(string $b, namespace\b\Stdclass $a) {
    return 1;
}

function x3(string $b, d\b\Stdclass $a) {
    return 1;
}

function x4(string $b, Stdclass $a) {
    return 1;
}

function x5(string $b, \Stdclass $a) {
    return 1;
}

?>