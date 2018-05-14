<?php

array_change_key_case($part, CASE_UPPER);
array_multisort($order, SORT_NUMERIC, SORT_DESC, $c->results);

error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(E_ALL | E_STRICT);

htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $charset);

?>