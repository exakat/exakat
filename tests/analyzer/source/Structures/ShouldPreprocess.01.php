<?php 
// OK     
    $a = 'a' . "b";
    $b = true * "b";
    $c = null +  2.2;
    $d = 'a' . strtolower("b");
    $d = 'a' . rand("b");
//  could do but needs 'determinist' functions
    $e = 2 << 3;
    $f = !(2 + 4 - 4);
    $g = (6 and 7);
    $h = 8 ^ 9;
    $h = 8 ** CONSTANTE;
    $i = 'a' . Stdclass::c;
    
// KOK
    $A = 'a' . $a;
    $B = __DIR__ + 1;
    $C = 'a' . $a->b;
    $E = 'a' . Stdclass::$D;
    $E = 'a' % Stdclass::D();
    $E = 'a' >> $c->d();
    
    
?>