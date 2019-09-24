<?php 
    function y() {
        static $unusedStatic;
        static $unusedStatic2 = 2;
        static $usedStatic, $usedStatic3, $usedStatic2;
        
        $usedStatic = $usedStatic2->m() + $usedStatic3[3];

    }

?>