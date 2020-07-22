<?php

function ($c, ) use ($a, $b,) : int|null {};

function ($c, ) use ($a, $b,) : int|float {};
function ($c, ) use ($a, $b,) : int|float|string {};
function ($c, ) use ($a, $b,) : int {};
function ($c, ) use ($a, $b,)  {};

class x {
    private x $a, $b, $c;
    protected $x;
    public $y;
}

?>