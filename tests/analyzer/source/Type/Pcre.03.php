<?php

preg_replace("%123$x4%u", $a, $b);
preg_replace_callback('%123'.$x.'4%u', $a, $b);

$A = '{__NORUNTIME__}';
$A = '__NOTREGEX__';

?>