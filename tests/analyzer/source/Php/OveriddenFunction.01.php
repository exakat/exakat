<?php

namespace A {
    function dirname($a, $b) {  echo __METHOD__;  }
    
    dirname(1, 2);
    dirnAME(1, 2, 3);
    \dirname(1, 2);
    implode('a', range(3, 4));
}
?>