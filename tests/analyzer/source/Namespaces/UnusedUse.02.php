<?php

namespace A;

use OriginalInNew as InNew;
use OriginalInTypeHint as InTypeHint;
use OriginalNeverUsed as NeverUsed;
use OriginalAlwaysUsed as AlwaysUsed;
use OriginalInExtends as InExtends;
use OriginalInImplementsAlone as InImplementsAlone;
use OriginalInImplementsAccompanied as InImplementsAccompanied;
use OriginalInStaticConstant as InStaticConstant;
use OriginalInStaticProperty as InStaticProperty;
use OriginalInStaticMethod as InStaticMethod;

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
