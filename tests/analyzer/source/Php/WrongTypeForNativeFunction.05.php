<?php

class x {
    private static $pVoid;
    private static string $pString = '2';
    private static int $pInt = 1;
    
    function foo() {
        substr(self::$pVoid, 0, 1);
        substr(self::$pString, 0, 1);
        substr(self::$pInt, 0, 1);
    }
}
?>