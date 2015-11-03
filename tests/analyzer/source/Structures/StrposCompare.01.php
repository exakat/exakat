<?php

strpos($a, $b) == false; // OK
STRpos($a, $b) != false; // OK
STRPOS($a, $b) != false; // OK


strPOS($a, $b) === false; // KO 
stRPOS($a, $b) === true;  // KO 

?>