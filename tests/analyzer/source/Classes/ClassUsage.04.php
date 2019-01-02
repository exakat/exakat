<?php

namespace A\B {
    class c {}

    class_alias('A\B\C', $c);
    
    class_alias($b, $a);
    
    $t->class_alias(1, 2);
}
?>