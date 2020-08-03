<?php

function &foo1($a) { return 1;}
function &foo1a(&$a) { return $a;}

function &foo2($a) { return 1 + $a;}
function &foo3($a) { return E_ALL;}

function bar2() : c { $a = 1; return $a;}
function bar3() : int { $a = 1; return $a;}


function bar1() { $a = 1; return $a;}
function &fooo1() { return bar1();}

function &fooo2() { return bar2();}
function &fooo3() { return bar3();}

function &bar4() { $a = 1; return $a;}
function &fooo4() { return bar4();}

?>