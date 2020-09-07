<?php

#[XAttribute ]
#[Attr1("foo")]
#[MyAttr([1, 2])]
#[Bar(2 * (3 + 3)>>Baz, (4 + 5) * 2)]
function foo() : array {}

?>