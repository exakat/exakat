<?php

    $a->b = fopen(__DIR__.'/server.log1', 'a');
    if ($a->b !== false) {
        fwrite($a->b, date('r')."\t$message\n");
        fclose($a->b);
    }

    $a->b = fopen(__DIR__.'/server.log3', 'a');
    if ($a->b != false) {
        fwrite($a->b, date('r')."\t$message\n");
        fclose($a->b);
    }

    $a->b = fopen(__DIR__.'/server.log2', 'a');
    fwrite($a->b, date('r')."\t$message\n");
    fclose($a->b);

?>