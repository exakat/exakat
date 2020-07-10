<?php

var_dump(match ($undefinedVariable) {
    null => 'null',
    default => 'default',
});

var_dump(match ($undefinedVariable) {
    1, 2, 3, 4, 5 => 'foo',
    default => 'bar',
});

?>