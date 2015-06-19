<?php

class_alias('a', 'b');
\class_alias($a, $b);

CLASS_ALIAS($b, 'C');

$this->class_alias(1,2);
?>