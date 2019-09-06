<?php

while(!empty($a)) { $v = array_pop($a); }
while(!empty($a)) { $v = array_shift($a); }

do {$v = array_pop($a); 1;} while(!empty($a));
do {$v = array_shift($a); 1;} while(!empty($a));

while(count($a) == 0) { $v = array_unshift($a); };
?>