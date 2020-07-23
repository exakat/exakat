<?php

// Normal (No static)
for($in = 0; $i<10; ++$i) { ++$for; }

foreach($an as $b) { ++$foreach; }

do ++$dowhile; while ($dn++); 

while ($en++) { ++$while; }

// Target (with static)
for($i = 0; $i<10; ++$i) { static $for; ++$for; }
for($i2 = 0; $i2<10; ++$i2) { ++$static; }

foreach($a as $b) { static $foreach; ++$foreach; }

do { static $dowhile; ++$dowhile; } while ($d++); 

while ($e++) { static $while; ++$while;}


?>