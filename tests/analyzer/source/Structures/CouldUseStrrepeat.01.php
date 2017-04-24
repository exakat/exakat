<?php

foreach($a as $b) {
    $x .= 'd';
}

for($a = 3; $a < 10; ++$a) {
    $x .= 'e';
}

// Not good : foo transforms $a
for($a = 3; $a < 10; ++$a) {
    $x .= foo($a);
}

?>