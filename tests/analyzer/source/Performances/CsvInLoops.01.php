<?php

$f = fopen('php://memory', 'w+');
foreach($a as $r) {
    fputcsv($f, $r);
}

$f2 = fopen('/path', 'w+');
foreach($a as $r) {
    fputcsv($f2, $r);
}

class x {
    private $f, $f2;
    
    function foo() {
        $this->f = fopen('php://memory', 'w+');
        foreach($a as $r) {
            fputcsv($this->f, $r);
        }
        
        $this->f2 = fopen('/path', 'w+');
        foreach($a as $r) {
            fputcsv($this->f2, $r);
        }
    }
}

fputcsv($a->b, $r);

?>