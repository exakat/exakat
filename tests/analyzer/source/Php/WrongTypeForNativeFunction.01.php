<?php

log(1);
log(2.1);
log(-1);
log(-1.2);
log("3");
log(array());
log(foo());
log(bar());
log(strtolower('3'));
log(exp('3'));

function foo() : ?float { return 1;}
function bar() : float { return 1;}
?>