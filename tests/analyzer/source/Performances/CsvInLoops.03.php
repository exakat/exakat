<?php

$f = fopen('PHP://temp', 'w+');
foreach($a as $r) {
    fputcsv($f, $r);
}

$f2 = fopen('/path', 'w+');
foreach($a as $r) {
    fputcsv($f2, $r);
}

class x {
    private static $f, $f2, $f3, $f4;
    
    function foo() {
        self::$f = fopen('PHP://temp', 'w+');
        foreach($a as $r) {
            fputcsv(self::$f, $r);
        }

        self::$f3 = fopen('PHP://temp/maxmemory:33', 'w+');
        foreach($a as $r) {
            fputcsv(self::$f3, $r);
        }
        
        self::$f4 = fopen('php://THemp/maxmemory:33', 'w+');
        foreach($a as $r) {
            fputcsv(self::$f4, $r);
        }
        
        self::$f2 = fopen('/path', 'w+');
        foreach($a as $r) {
            fputcsv(self::$f2, $r);
        }
    }
}

// We know nothing abou this
// and it's not in a loop
fputcsv($a->b, $r);

?>