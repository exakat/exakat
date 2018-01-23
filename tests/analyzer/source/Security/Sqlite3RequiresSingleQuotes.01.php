<?php

// KO. escapeString is not OK with "
$query = 'select * from table where col = "'.$sqlite->escapeString($x).'"';

// OK. escapeString is OK with '
$query = "select * from table where col = '".$sqlite->escapeString($x)."'";

$query = 'select * from table where col = "'.escapeString($x).'"';


$x = $sqlite->escapeString($x);
$query = 'select * from table where col = "'.$x.'"';

?>