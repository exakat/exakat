<?php


namespace A {
    const E = 'e';
    const C = 'c';
    const A = 'a';
    // B is not defined. 
}


namespace B {
    use const A\A, B;
    use const A\{D, E};
    
    use A\{ B,
        const C,
        function D };
    //B, 
    
    echo C, A, B, D, E, \A\A, \PHP_INT_MAX, F;
}
