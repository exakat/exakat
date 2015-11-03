<?php

namespace A;

use InNew;
use InTypeHint;
use NeverUsed;
use AlwaysUsed;
use InExtends;
use InImplementsAlone;
use InImplementsAccompanied;
use InStaticConstant;
use InStaticProperty;
use InStaticMethod;

function c( InTypeHint $d) {}
function c2( AlwaysUsed $d) {}

$x = new InNew;
$y = new AlwaysUsed();

class B1 extends InExtends {}
class B2 implements InImplementsAlone {}
class B3 implements InImplementsAccompanied, AlwaysUsed {}

InStaticConstant::SC;
InStaticProperty::$y;
InStaticMethod::x();

?>
