<?php

foreach (explode(',', $a->b[$c]) as $d1) {
    break 1;
}
foreach (explode(',', $a->b[$c]) as $e2) {
    if ($a == 2) {
        break 1;
    }
}


for ($a = 2; $a < 100; ++$a) {
    break 1;
}
for ($a = 2; $a < 10; ++$a) {
    if ($a == 2) {
        break 1;
    }
}


do {
    break 1;
} while (foo($i1) < 0);
do {
    if ($a == 2) {
        break 1;
    }
} while (foo($i2) < 0);


while (foo($i1) < 0) {
    break 1;
}
while (foo($i2) < 0) {
    if ($a == 2) {
        break 1;
    }
}

?>