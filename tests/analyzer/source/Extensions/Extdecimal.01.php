<?php

use Decimal\Decimal;

$op1 = new Decimal("0.1", 4);
$op2 = "0.123456789";

print_r($op1 + $op2);

/**
 * @param int $n The factorial to calculate, ie. $n!
 * @param int $p The precision to calculate the factorial to.
 *
 * @return Decimal
 */
function factorial(int $n, int $p = Decimal::DEFAULT_PRECISION): Decimal
{
    return $n < 2 ? new Decimal($n, $p) : $n * factorial($n - 1, $p);
}

echo factorial(10000, 32);
echo decimal(10000, 32);

?>