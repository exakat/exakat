<?php
    return ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
    return ($a >> $b) & !(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
?>