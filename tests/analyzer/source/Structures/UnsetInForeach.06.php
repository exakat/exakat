<?php

    foreach ($a as $b => $c) {
        unset($c['d']);
    }

    foreach ($a as $b => $c2) {
        unset($c->d);
    }

    foreach ($a as $b => $c3) {
        unset($z, $x, $c3);
    }

?>