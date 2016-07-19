<?php

namespace A;

use a\b\c\d\OriginalInNew as InNew;
use a\b\c\d\OriginalInTypeHint as InTypeHint;
use a\b\c\d\OriginalNeverUsed as NeverUsed;
use a\b\c\d\OriginalAlwaysUsed as AlwaysUsed;
use a\b\c\d\OriginalInExtends as InExtends;
use a\b\c\d\OriginalInImplementsAlone as InImplementsAlone;
use a\b\c\d\OriginalInImplementsAccompanied as InImplementsAccompanied;
use a\b\c\d\OriginalInStaticConstant as InStaticConstant;
use a\b\c\d\OriginalInStaticProperty as InStaticProperty;
use a\b\c\d\OriginalInStaticMethod as InStaticMethod;

function c( InTypeHint\B $d) {}
function c2( AlwaysUsed\B $d) {}

$x = new InNew\B;
$y = new AlwaysUsed();

class B1 extends InExtends\B {}
class B2 implements InImplementsAlone\B {}
class B3 implements InImplementsAccompanied\B, AlwaysUsed\B {}

InStaticConstant\B::SC;
InStaticProperty\B::$y;
InStaticMethod\B::x();

?>
