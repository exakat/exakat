<?php

// Normal (No global)
for($in = 0; $i<10; ++$i) { ++$for; }

foreach($an as $b) { ++$foreach; }

do ++$dowhile; while ($dn++); 

while ($en++) { ++$while; }

// Target (with global)
for($i = 0; $i<10; ++$i) { global $for; ++$for; }
for($i2 = 0; $i2<10; ++$i2) { ++$global; }

foreach($a as $b) { global $foreach; ++$foreach; }

do { global $dowhile; ++$dowhile; } while ($d++); 

while ($e++) { global $while; ++$while;}


?>