<?php

class x {
    function foo (parent $parentMethod, self $selfMethod) {
        $aParent instanceof parent;
        $aSelf instanceof self;
        $aStatic instanceof static;
    }
}

//function foo(parent $parentFunction, self $selfFunction) {}
function (parent $parentClosure, self $selfClosure) {};

function foo2() {
//        $bParent instanceof parent;
//        $bSelf instanceof self;
//        $bStatic instanceof static;
}
?>