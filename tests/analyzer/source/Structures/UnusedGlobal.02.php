A<?php 
    function y() {
        global $unusedGlobal;
        global $unusedGlobal1, $unusedGlobal2;
        
        global $usedGlobal;
        global $usedGlobal1, $usedGlobal2;
        
        $usedGlobal = $usedGlobal1 + $usedGlobal2;
    }

    function y2() {
        global $unusedGlobaly2;
        global $unusedGlobal1y2, $unusedGlobal2y2;
        
        global $usedGlobaly2;
        global $usedGlobal1y2, $usedGlobal2y2, $usedGlobal2y3;
        
        $usedGlobaly2->a = $usedGlobal1y2[1][2] + $usedGlobal2y2 + $usedGlobal2y3;
    }

        global $unusedGlobalglb;
        global $unusedGlobal1glb, $unusedGlobal2glb;
        
        global $usedGlobalglb;
        global $usedGlobal1glb, $usedGlobal2glb;
        
        $usedGlobalglb::$a = "$usedGlobal1glb" + $$usedGlobal2glb;

?>B