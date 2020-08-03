<?php
function foo($a, $b = array()) {}
foo(1);

function goo($a, ...$b) {}
goo(1, 2);

?>