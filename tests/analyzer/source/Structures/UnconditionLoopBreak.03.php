<?php

foreach (explode(',', $a->b[$c]) as $d1) {
    continue;
}
foreach (explode(',', $a->b[$c]) as $e2) {
    if ($a == 2) {
        continue;
    }
}


for ($a = 2; $a < 100; ++$a) {
    continue;
}
for ($a = 2; $a < 10; ++$a) {
    if ($a == 2) {
        continue;
    }
}


do {
    continue;
} while (foo($i1) < 0);
do {
    if ($a == 2) {
        continue;
    }
} while (foo($i2) < 0);


while (foo($i1) < 0) {
    continue;
}
while (foo($i2) < 0) {
    if ($a == 2) {
        continue;
    }
}

?>