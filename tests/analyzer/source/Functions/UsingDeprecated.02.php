<?php


class x {
    /**
     * @deprecated
     */
    function deprecated() {
    }

    /**
     * This is not deprecated
     */
    function not_deprecated() {
    }
}

function foo(x $x) {
    $x->deprecated();
    $x->not_deprecated();
}

?>