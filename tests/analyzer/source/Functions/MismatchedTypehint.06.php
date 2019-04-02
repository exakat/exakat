<?php

interface i {}

class w { }

class x extends w implements i {
    function barW(W $a) {}
    function barX(X $a) {}
    function barY(Y $a) {}
    function barZ(Z $a) {}
    function bari(i $a) {}
}

class z extends x {
}

function bar(X $aXW, X $aXX, X $aXY, X $aXZ, X $aXi) {
    $x = new x;
    $x->barW($aXW);
    $x->barX($aXX);
    $x->barY($aXY);
    $x->barZ($aXZ);
    $x->bari($aXi);
}