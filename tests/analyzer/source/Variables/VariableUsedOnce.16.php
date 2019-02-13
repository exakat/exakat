<?php

$e = 'a';
$e();
        $d = $a->b();

        array_map(function ($c) use ($d) {
            return $c + $d;
            }
        );

?>