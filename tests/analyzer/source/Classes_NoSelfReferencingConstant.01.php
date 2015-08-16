<?php

class a { 
    const NORMAL_C = M_PI + 2;  // OK
    const NORMAL_C2 = 3 * 2;    // OK

    const C = self::C;          // No Self
    const UPPERC = SELF::C;     // No SELF 

    const CSE_C = SELF::C + 1;  // Composed
} 

a::C;

?>