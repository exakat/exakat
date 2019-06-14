<?php

namespace {
    array_key_exists($a1, $b);
    \array_key_exists($a2, $b);
}

namespace B {
    use function array_key_exists as foo;
    use function array_key_exists;
    
    array_key_exists($a3, $b);
    \array_key_exists($a4, $b);
    foo($a, $b);
}

namespace B {
    use array_key_exists;

    array_key_exists($a5, $b);
    \array_key_exists($a6, $b);
    foo($a, $b);
}

?>