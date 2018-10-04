<?php

function x () {
    $fromXInUse1 = 2;
    $fromXInUse1AndInCLosure = 2;
    $fromXInUse2 = 2;
    $fromXInUse2AndInCLosure = 2;
    $fromXOnly = 2;
    $fromXOnlyTwice = 2;
    $fromXOnlyTwice++;
    
    function ($ca1, $cb1) use ($fromXInUse1, $fromXInUse1AndInCLosure) { 
        $onlyInCLosure = 1 + $fromXInUse1 + $fromXInUse1;
        $fromXInUse1AndInCLosure = 2;
        $seemsInBothClosuresButNot = 1;
    };

    function ($ca2, $cb2) use ($fromXInUse2, $fromXInUse2AndInCLosure) { 
        $onlyInCLosure = 1 + $fromXInUse2 + $fromXInUse2;
        $fromXInUse2AndInCLosure = 2;
        $seemsInBothClosuresButNot = 1;
    };

}