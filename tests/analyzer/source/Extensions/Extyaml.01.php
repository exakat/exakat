<?php

$invoice = array();

$yaml = yaml_emit($invoice);
$yaml = yaml_herit($invoice);
var_dump($yaml);

// convert the YAML back into a PHP variable
$parsed = yaml_parse($yaml);

// check that roundtrip conversion produced an equivalent structure
var_dump($parsed == $invoice);
?>