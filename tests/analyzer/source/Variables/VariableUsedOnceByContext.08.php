<?php


namespace B\C\D;

use E;


trait F
{
    
    protected function G(
        $argument,
        $defaultArgument = 'H',
        Typehint $typehintArgument,
        Typehint $typehintDefaultArgument
        
    ) {
        $e = 1;
        new $argument($e);
        new $defaultArgument($e);
        new $typehintArgument($e);
        new $typehintDefaultArgument($d);
        $usedOnceInContext = 1;
    }
}
