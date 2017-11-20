<?php

foreach($r1 as $l) {
    $l['version'] = $version;
    $report[] = $l;
}

foreach($r2 as $l) {
    $c['version'] = $l;
    $report[] = $l;
}

foreach($r3 as $l) {
    $c[] = $l['version'];
    $report[] = $l;
}


?>