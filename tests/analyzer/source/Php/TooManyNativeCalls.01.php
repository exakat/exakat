<?php

echo strtolower($a);
echo strtolower($a), strtolower($b);
echo strtolower($a), strtolower($b), strtoupper($c), strtoupper($d);
echo ucfirst(strtolower($a)), ucfirst($b);
echo ucfirst(strtolower($a)), ucfirst(strtolower($b));
foo(ucfirst(strtolower(join(', ', split(';', $a)))));

?>