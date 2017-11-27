<?php

namespace cmp {
    function b() {}
}

namespace {
function cmpUsed ($a, $b) { return true; }
function cmpUsedFullnspath ($a, $b) { return true; }
function cmpNotUsed ($a, $b) { return true; }


array_map('cmpUsed', range(1, 10));
array_map('\\cmpUsedFullnspath', range(1, 10));
array_map('\\cmp\\b', range(1, 10));

}

?>