<?php 
//	global $curlContent;
/*
    // $y used only in x() : Should be static. 
    function x() {
        global $y;
        
        $y++;
    }
*/    

    // global used only once
    
// OK     
    $a = 'a' . "b";
    $b = true * "b";
    $c = null +  2.2;
//    $d = 'a' . strtolower("b");
//  could do but needs 'determinist' functions
    $e = 2 << 3;
    $f = !(2 + 4 - 4);
    $g = 6 and 7;
    $h = 8 ^ 9;
    
// KOK
    $A = 'a' . $a;
    $B = __DIR__ + 1;
    $C = 'a' . $a->b;
    $D = 'a' . Stdclass::c;
    $E = 'a' . Stdclass::$D;
    $E = 'a' % Stdclass::D();
    $E = 'a' >> $c->d();
    
    
?>