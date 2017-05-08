<?php

class a { 
    const C = 1; 
    const C1 = a::C1; 
    const C3 = a::c3; 
    const c4 = a::C4; 
    
    const C12 = a::C22;   // OK
    const C2 = a::C2 + 2; 

    const D1 = a::D1 + 1; 
    const D3 = a::d3 + 2; 
    const d4 = a::D4 + 3; 
    
    const E = 3;

} 

print a::C1;

?>