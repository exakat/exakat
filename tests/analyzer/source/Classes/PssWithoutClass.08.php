<?php

interface i {
    const A = 4;
    const B = 5;
    
    function asdf ( $d, 
                    $e = self::B,
                    $f = self::C ) ;
}

echo self::CE;
?>