<?php

function foo() { 
    if ($a) {  
    } 
    
    ++$a;
}

function foo2() { 
    if ($a2) {  
        return;
    } else {
    
    }
    ++$a;
}

function foo3() { 
    if ($a3) {  
        return;
    } 
    ++$a;
    ++$b;
}

?>