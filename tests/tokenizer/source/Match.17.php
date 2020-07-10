<?php

try {
    var_dump(match(true) {
        1, 2, 3, 4, 5 => 'foo',
    });
} catch (Error $e) {
    var_dump((string) $e);
}

try {
    var_dump(match(6) {
        1, 2, 3, 4, 5 => 'foo',
    });
} catch (Error $e) {
    var_dump((string) $e);
}

try {
    var_dump(match('3') {
        1, 2, 3, 4, 5 => 'foo',
    });
} catch (Error $e) {
    var_dump((string) $e);
}

var_dump(match(3) {
    1, 2, 3, 4, 5 => 'foo',
});

var_dump(match(true) {
    1, 2, 3, 4, 5 => 'foo',
    default => 'bar',
});

var_dump(match(6) {
    1, 2, 3, 4, 5 => 'foo',
    default => 'bar',
});

var_dump(match('3') {
    1, 2, 3, 4, 5 => 'foo',
    default => 'bar',
});

var_dump(match(3) {
    1, 2, 3, 4, 5 => 'foo',
    default => 'bar',
});
