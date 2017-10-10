<?php

    $fp = fopen(__DIR__.'/server.log1', 'a');
    if ($fp !== false) {
        fwrite($fp, date('r')."\t$message\n");
        fclose($fp);
    }

    $fp = fopen(__DIR__.'/server.log3', 'a');
    if ($fp != false) {
        fwrite($fp, date('r')."\t$message\n");
        fclose($fp);
    }

    $fp = fopen(__DIR__.'/server.log2', 'a');
    fwrite($fp, date('r')."\t$message\n");
    fclose($fp);

?>