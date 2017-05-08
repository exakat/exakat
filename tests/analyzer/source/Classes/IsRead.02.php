<?php

parse_str($read->a, $written->b);

custom_function($read2->c, $written2->d);
custom_function($read3->e, $written3->f, $ignored3->g);

function custom_function ($read_arg, &$written_arg) { }

$writtenOnly = 3;

?>