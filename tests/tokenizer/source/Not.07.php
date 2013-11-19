<?php

    if (!$handle = fopen('/tmp/test', 'r+b')) {
        echo 1;
    }
?>