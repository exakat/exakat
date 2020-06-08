<?php
    /**
     * @deprecated since Symfony 5.1, to be removed in 6.0. Use symfony/redis-messenger instead.
     */
function deprecated() {
}

/**
 * @DEPRECATED
 */
function DEPRECATED_TOO() {
    if ($a == 1) {
        $a++;
    } else {
        $a++;
    }
}

    /**
     * This is not deprecated
     */
function not_deprecated() {
}

/**
 * @deprecated
 */
function deprecated_but_unused() {
}

deprecated();
DEPRECATED_TOO();
/** @deprecated but in functioncall */
not_deprecated();

?>