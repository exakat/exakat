<?php

namespace A;

use const A\B as C;

const B =1;

namespace\foo();

$a instanceof namespace\C;

echo B;
echo A;
echo \B;
echo \A\B;
echo C; 
echo namespace\B;
echo namespace\C;
