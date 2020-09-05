<?php

function foo() { return 'string'; }
function foo2() { return 'string'; }
function foo3() : int { return 'string'; }

echo substr(foo(), 2, 3);
echo substr(foo2(), 2, 3);
echo substr(foo3(), 2, 3);

