<?php

class x {}

interface NormalInterface {}
trait NormalTrait {}

define('NormalConstant', 1);
const NormalConstantWithConst = 1;

function normalFunction () {}

if ($condition) {
    class conditionnedX {
        const ClassConstan = 1;
        function Method() {}
        
    }
    interface conditionnedInterface {}
    trait conditionnedTrait {}
    function conditionnedFunction () {}

    define('ConditionedConstant', 1);
    
}
?>