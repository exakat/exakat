<?php
<<AttributeWithScalarExpression(1+1)>>
<<AttributeWithClassNameAndConstants(PDO::class, PHP_VERSION_ID)>>
<<AttributeWithClassConstant(Http::POST)>>
<<AttributeWithBitShift(4 >> 1, 4 << 1)>>
function foo() {}

$closure = <<ClosureAttribute>> fn() => 1;

function foo2(<<ArgumentAttribute>> $bar) {  }

<<MethodAttribute1>>
/** @return void */
<<MethodAttribute2>>
function doSomething(): void {  }

?>