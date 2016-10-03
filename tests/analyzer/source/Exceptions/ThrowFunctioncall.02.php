<?php

if ($a === 2) {
    throw $a->b[$c];
}

throw new $a->b[$c]();

throw $a->b[$c]();

throw new $a->b[$d];

throw $a->b[$d];

throw $e;

?>