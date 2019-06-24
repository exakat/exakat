<?php

const C = 'c';
const E = array();


foo();
foo2();
foo3();
foo4();
foo5();

function foo($a = '', $f) {
    $a[] = 2;
    
    $f = '';
    $f[3] = 3;
}

class x {
    private $b = ''."b";
    private $c = ['a'];

    function foo2() {
        $this->b[3] = 2;
        $this->c[3] = 2;

        $this->d = '2';
        $this->d[3] = 2;

        $this->e = array(2);
        $this->e[3] = 2;
    }
}

?>