<?php

foreach($a as $b) {
    $c = 'd' . $c;
}

for($i = 0; $i < 10; $i++) {
    $c['d'] = $i.$c['d'];
}

foreach($a2 as $b2) {
    $c = $d + $c->d;
}

for($i2 = 0; $i2 < 10; $i2++) {
    $c = $i2 * $c->d;
}

?>