<?php

define('+3', 1); // wrong constant! 
define('3foo', 2); // wrong constant! 
define('$foo', 3); // wrong constant! 
define('f$oo', 3); // wrong constant! 

define('我', 4); // but this is OK!
define('A我', 4); // but this is OK!
define('我A', 4); // but this is OK!


define("FOO",     "something");
define("FOO2",    "something else");
define("FOO_BAR", "something more");
define('fo3o', 2); // wrong constant! 
define("frânçaïs",     "oui!");

?>