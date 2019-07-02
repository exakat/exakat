<?php

function functionWithReturn ($x ) { 
    switch($y) { case 1 : return 1;}
    $x  = $x + 1; 
}

function functionWithReturnInClosure ($x ) { 
    switch($y) { case 1 : $x = function () { return 2;};}
    $x  = $x + 1; 
}

function functionWithReturnInFunction ($x ) { 
    switch($y) { case 1 : function c() { return 2;}}
    $x  = $x + 1; 
}

function functionWithoutReturn ($x ) { 
    switch($y) { case 1 : 1;}
    $x  = $x + 1; 
}

?>