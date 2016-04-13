<?php

class A extends \Exception {}
class B extends A {}
class C extends B {}

try {
    something();
} 
catch(A $a1) { }
catch(B $b2 ) { }
catch(C $c3 ) { }

try {
    something();
} 
catch( A $a1) { }
catch( C $c2) { }
catch( B $b3) { }

try {
    something();
} 
catch(B $b1) { }
catch(C $c2) { }
catch(A $a3) { }

try {
    something();
} 
catch(B $b1) { }
catch(A $a2) { }
catch(C $c3) { }

try {
    something();
} 
catch(C $c1) { }
catch(B $b2) { }
catch(A $a3) { }

try {
    something();
} 
catch(C $c1) { }
catch(A $a2) { }
catch(B $b3) { }


?>