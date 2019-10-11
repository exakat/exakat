<?php

function foo() : int {
    return $c1 + $c2;
    return $c1 * $c2;
    return $c1 / $c2;
    return $c1 ** $c2;
    return (string) $c1;
    return (int) $c2;
}

function foo2() : string {
    return $c1 . $c2;
    return (string) $c;
    return (int) $c;
    return <<<HEREDOC

HEREDOC;
}

function foo3() : boolean {
    return !$c1;
    return $a == $b;
    return (bool) $c;
}

const C = [334];
function foo4() : float {
    return (float) $c;
    return $c ? 5.6 : 'dfdfs';
    return 4.3;
    return 'dfdfs';
    return 7;
    return  C;
}

function foo5() : object {
    return new $a;
    return clone $b;
    return (object) $c;
    return ($c);
    return (clone $d);
}

?>