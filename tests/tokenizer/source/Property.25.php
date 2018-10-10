<?php

$a = 1;

$b = 'c';
$bb = 'c';
$a = new x;
echo $a->{$b};
echo $a->${'b'.strtolower('b')};
echo $a->{${'b'.strtolower('b')}};
echo $a->${${'b'.strtolower('b')}};

class x { public $c = 'd';}

?>