<?php

A:

foreach (explode(',', $a->b[$c]) as $d1) {
    goto A;
}
foreach (explode(',', $a->b[$c]) as $e2) {
    if ($a == 2) {
        goto A;
    }
}


for ($a = 2; $a < 100; ++$a) {
    goto A;
}
for ($a = 2; $a < 10; ++$a) {
    if ($a == 2) {
        goto A;
    }
}


do {
    goto A;
} while (foo($i1) < 0);
do {
    if ($a == 2) {
        goto A;
    }
} while (foo($i2) < 0);


while (foo($i1) < 0) {
    goto A;
}
while (foo($i2) < 0) {
    if ($a == 2) {
        goto A;
    }
}

?>