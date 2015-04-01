<?php
global $$foo->bar;
global $$foo1->bar1, $$foo2->bar2, $$foo3->bar3 ; // ${ $foo->bar}

global ${$foo->bar}; // OK

?>