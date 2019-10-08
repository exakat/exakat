<?php

var_dump(filter_var("'", FILTER_SANITIZE_MAGIC_QUOTES));
var_dump(filter_var("'", \FILTER_SANITIZE_MAGIC_QUOTES));
var_dump(filter_var("'", filter_sanitize_magic_quotes));
var_dump(filter_var("'", c::filter_sanitize_magic_quotes));

?>