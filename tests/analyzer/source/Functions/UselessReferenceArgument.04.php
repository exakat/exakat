<?php

foreach($a as $b) { $b->a; }
foreach($a as &$b2) { $b2->a; }
foreach($a as $k => &$b3) { $b3->a; }
foreach($a as &$b4) { $b4->a(); }
foreach($a as $k => &$b5) { $b5->a(); }
foreach($a as &$b6) { $b6[3]->a(); }

?>