<?php

fn &($a1) => 1;
fn &(&$a2) => $a;

fn &($a3) => 1 + $a;
fn &($a4) => E_ALL;

function bar2() : c { $a = 1; return $a;}
function bar3() : int { $a = 1; return $a;}

function bar1() { $a = 1; return 1;}
fn &($fooo1) => bar1();

fn &($fooo2) => bar2();
fn &($fooo3) => bar3();

function bar4() { $a = 1; return 4;}
fn &($fooo4) => bar4();

?>