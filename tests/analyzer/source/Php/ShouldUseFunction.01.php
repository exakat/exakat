<?php

namespace X {

    use function strtoupper;
    
    //
    $a = [1,2,3];         // No need for \\
    $b = array(4,5,6);    // No Needs \\ (actually impossible)
    
    $d = strtolower('a'); // Needs \\
    $e = strtoupper('a'); // OK, Aliased with use
    print $e;
    
    
//    $e->empty(); // This is a method
//    D::empty(); // This is a static method
//    new Stdclass; // This is an instantiation
    
}