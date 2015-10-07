<?php

print inGlobalScope::class;

function x () {
    print inFunctionScope::class;
}

trait t {
    function tx () {
        print inTraitMethodScope::class;
    }
}

?>