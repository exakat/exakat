<?php

function x (\Reflector $x) {}
function y (\Stdclass $x) {}

if ($y instanceof Reflector) { }
if ($y instanceof Stdclass) { }

?>