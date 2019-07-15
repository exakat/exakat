<?php

foreach($array as $row) {
    $b[$row['a']][] = $row['c'];
}

foreach($array as $row2) {
    $b[] = $row2['c'];
}

foreach($array as $row3) {
    $b[$row[3]] = 'c';
}

?>