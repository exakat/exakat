<?php

class A {}
class B {}
class C {}
class D {}
    
static $a = array(
        A::class => '1',
        B::class => '2',
        C::class => '3',
        D::class => '4',
);

static $b = array(
        A::class => '1',
        B::class => '2',
        B::class => '3',
        D::class => '4',
        D::class => '4',
        D::class => '4',
        D::class => '4',
);

static $c = array(
        A::class => '1',
        B::class => '3',
        'A'      => '4',
);


?>