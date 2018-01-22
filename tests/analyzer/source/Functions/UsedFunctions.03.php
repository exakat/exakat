<?php

namespace cmp {
    function b1() {}
    function b2() {}
    function b3() {}
    function b4() {}
    function b5() {}
}

namespace {
function cmpUsed ($a, $b) { return true; }

function cmpUsedFullnspath1 ($a, $b) { return true; }
function cmpUsedFullnspath2 ($a, $b) { return true; }
function cmpUsedFullnspath3 ($a, $b) { return true; }
function cmpUsedFullnspath4 ($a, $b) { return true; }

function cmpNotUsed ($a, $b) { return true; }


array_map('cmpUsed', range(1, 10));

array_map('\\cmpUsedFullnspath1', range(1, 10));
array_map('\cmpUsedFullnspath2', range(2, 10));
array_map('\CMPUSEDFULLNSPATH3', range(3, 10));
array_map('\\CMPUSEDFULLNSPATH4', range(4, 10));

array_map('\\cmp\\b1', range(1, 10));
array_map('\cmp\b2',   range(2, 10));
array_map('\CMP\b3',   range(3, 10));
array_map('\CMP\B4',   range(4, 10));
array_map('\cmp\B5',   range(5, 10));

}

?>