<?php

foreach($a as $b) {
    array_pop($a);
}

do 
    array_shift($a);
while(!empty($a));

while(!empty($a)) {
    if ($b > 1) {
        array_pop($a);
    }
}

?>