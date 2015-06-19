<?php
 $a = 0;
 foreach (B::C as $b => $c) {
     $a += $this->$b * $c;
 }
 $d = D($a, 0, 'E', 'F');
