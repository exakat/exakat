<?php

usort($a, function ($x, $y) {} );

uksort($a, 'callback');

\uasort($a, array('someClass', 'callback'));

$a->uasort($aMethod);

A::uksort($aStaticMethod);

?>