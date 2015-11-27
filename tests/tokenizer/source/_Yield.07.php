<?php
function B( ): Generator {
        yield yield;
        while (yield !== null);
}


foreach(B() as $c) {
    var_dump($c);
}