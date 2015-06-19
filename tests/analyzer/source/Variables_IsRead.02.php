<?php

parse_str($read, $written);

custom_function($read2, $written2);
custom_function($read3, $written3, $ignored3);

function custom_function ($read_arg, &$written_arg) { }

?>