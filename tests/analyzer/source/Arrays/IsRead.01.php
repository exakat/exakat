<?php

parse_str($read[1], $written[2]);

custom_function($read2[1], $written2[1]);
custom_function($read3[1], $written3[2], $ignored3[3]);

function custom_function ($read_arg, &$written_arg) { }

$readadd[1] + $readadd[1][2] + $readadd[1][2][3];

$written_only[3] = $readAssignation[3];

?>