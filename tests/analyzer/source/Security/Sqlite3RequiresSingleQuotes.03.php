<?php

$query = "(null, '" . $a->sqlite->escapeString($name) . "',  '$type',   $count)";

$query = '(null, "' . $a->sqlite->escapeString($name) . '",  \'$type\',   $count)';

$query = "(null, \"" . $a->sqlite->escapeString($name) . "\",  '$type',   $count)";

?>