<?php

for($i = 0; $i < 10; ++$i) {
    mb_substr($str, $i, 1);
}

foreach(range(0, 10) as $j) {
    mb_substr($str, $j, 1);
}

foreach(range('a', 'z') as $s) {
    mb_substr($s, 3, 1);
}


?>