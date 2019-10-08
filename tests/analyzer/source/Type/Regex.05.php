<?php

preg_replace('/a/', $a);
preg_replace(array('/b1/', '/b2/'), $b);
preg_replace(array('/c1/', 3 => '/c2/'), $c, $d);


const REGEX = array('/e1/', '/e2/');
preg_replace(REGEX, $b);

$z = '/ze/';

class x {
    private $regex = array('/f1/', '/f2/');
    
    const REGEX = array('/g1/', '/g2/');
    
    function foo() {
        preg_replace(self::REGEX, $b);
        preg_replace($this->regex, $b);

        $regex = array('/d1/', '/d2/');
        preg_replace($regex, $b);

    }
}

?>