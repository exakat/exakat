<?php

const CLOSE_QUOTE = "'";
const CLOSE_DOUBLE_QUOTE = '"';

// KO. escapeString is not OK with "
$query = 'select * from table where col = "'.$sqlite->escapeString($x).CLOSE_DOUBLE_QUOTE;

// OK. escapeString is OK with '
$query = "select * from table where col = '".$sqlite->escapeString($x).CLOSE_QUOTE;

$query = 'select * from table where col = "'.escapeString($x).CLOSE_DOUBLE_QUOTE;


$x = $sqlite->escapeString($x);
$query = 'select * from table where col = "'.$x.CLOSE_DOUBLE_QUOTE;


?>