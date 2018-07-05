<?php

namespace A {
    ALABEL_NOT_A_CONST : 
    
    goto ALABEL_NOT_A_CONST;
    
    const DEFINED_CONST = 1;
    
    echo A_CONST;
    echo \A_CONST;
    echo DEFINED_CONST;
    
    echo E_NOTICE;
    echo \E_NOTICE;
    echo A\E_NOTICE;
    
    function foo() {
        ALABEL_NOT_A_CONST_IN_FUNCTION : 
    
        goto ALABEL_NOT_A_CONST_IN_FUNCTION;
    
    }
}
?>