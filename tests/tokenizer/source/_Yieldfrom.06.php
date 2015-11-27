<?php
function B( ): Generator {
        yield from yield from foo();
        while (yield from foo() !== null);
}


foreach(B() as $c) {
    var_dump($c);
}