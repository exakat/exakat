<?php 

class x {
    function __construct() { echo __CLASS__;}
}

// Both are OK
class_alias('\x', '\x2');
class_alias('x', 'x3');

new x2;
new x2();
new \x2;
new \x2();

new x3;
new x3();
new \x3;
new \x3();

new x4;
new x4();
new \x4;
new \x4();

?>