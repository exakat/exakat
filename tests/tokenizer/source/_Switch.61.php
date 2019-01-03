<?php 
    switch($a) : 
    case "b" : ?>A<?php 
        ++$a; 
    ?>C<?php 
    case "b" ?>B<?php 
        ++$a; 
    ?>B<?php 
    endswitch; 
?>