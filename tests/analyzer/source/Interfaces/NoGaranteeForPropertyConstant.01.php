<?php

interface i { function m() ;}
class ci implements i {
    function m() {}
}
function foop(i $i) { echo $i->p; $i::$p;}
function fooc(i $i) { echo $i::c;}
function foom(i $i) { echo $i->m(); $i::m();}

function foopc(ci $i) { echo $i->p;}
function foocc(ci $i) { echo $i::c;}
function foomc(ci $i) { echo $i->m();}

?>