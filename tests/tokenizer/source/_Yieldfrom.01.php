<?php
function foo() {
    yield 1;
    return yield from bar();
}
 
function bar() {
    yield 2;
    yield 3;
    return 4;
}
 
$baz = foo();
foreach ($baz as $element) {
    echo $element, "\n";
}
echo $baz->getReturn(), "\n";
 
// 1
// 2
// 3
// 4