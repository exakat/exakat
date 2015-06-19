<?php

function simple($a) {}
function simpleWithDefault($b = 1) {}
function simpleWithTypehint(TH1 $c) {}
function simpleWithTypehintAndDefault(TH2 $d = null) {}
function simpleWithNSTypehint(NS1\TH1 $e) {}
function simpleWithNSTypehintAndDefault(NS2\TH2 $f = null) {}

?>