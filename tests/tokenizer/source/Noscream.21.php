<?php

try {
    $result = @(1.0 / 0);

    if (in_array($result, [INF, NAN])) {
        throw new DivisionByZeroError('Division by zero error');
    }
} catch (Error $e) {
    echo $e->getMessage();
}

?>