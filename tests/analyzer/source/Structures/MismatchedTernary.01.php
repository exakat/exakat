<?php
$object = ($type == 'Type') ? new $type() : null;
$object = ($type == 'Type') ? 1 : array();
$object = ($type == 'Type') ? 'a' : <<<H
HEREDOC
H;

$object = ($type == 'Type') ? 'a' : 'a' . C;


$a = 1;
$result = empty($condition) ? $a : 'default value';
$result = empty($condition2) ? $a : getDefaultValue();

?>