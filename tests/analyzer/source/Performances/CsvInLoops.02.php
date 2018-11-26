<?php

$f = fopen('PHP://MEMORY', 'w+');
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
        $this->f = fopen('PHP://MEMORY', 'w+');
        foreach($a as $r) {
            fputcsv($this->f, $r);
        }
        
        $this->f2 = fopen('/path', 'w+');
        foreach($a as $r) {
            fputcsv($this->f2, $r);
        }
    }
}

// We know nothing abou this
// and it's not in a loop
fputcsv($a->b, $r);

?>