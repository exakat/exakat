<?php

\define('+3', 1); // wrong constant! 
$x->define('3method', 2); // not a functioncall !
Stdclass::define('4staticmethod', 3); // not a functioncall !

?>