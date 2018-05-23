<?php

foreach($a as $b) {
    $d = $a->method(__DIR__);
    $d = $a->method(__DIR__.$b);
    $d = $a->method(__DIR__.$a);
}

foreach($a as $d => $e) {
    $d = C::staticmethod($x->x);
    $d = C::staticmethod($x->x + $a);
    $d = C::staticmethod($x->x + $e);
    $d = C::staticmethod($x->x + $d);
}


?>