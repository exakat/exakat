<?php

class x {
    const UNUSED = 1;
    const USED1 = 1;
    private const USED2 = 2, USED3 = 4;
    
    function foo() {
        echo self::USED2 + x::USED3;
    }
}

echo x::USED1;
?>