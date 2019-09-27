<?php

foreach($a as ['a' => &$g]) {
    $g->go();
}

foreach($a2 as ['a' => ['b' => &$h]]) {
    $h->go();
}

foreach($a3 as ['a' => ['b' => $i]]) {
    $i->go();
}


?>