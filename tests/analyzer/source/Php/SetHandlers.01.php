<?php

function x() {}
register_shutdown_function('x');

$x->register_shutdown_function('c');

?>