<?php

// Same subjects
preg_replace_callback($a1, $b1, $c);
preg_replace_callback($a2, $b2, $c);

// Different subjects
preg_replace_callback($a1, $b1, $c2);
preg_replace_callback($a2, $b2, $c3);


?>