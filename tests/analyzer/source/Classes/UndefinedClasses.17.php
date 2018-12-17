<?php

class A  {
    function __construct() { echo __CLASS__.PHP_EOL;}
}

//class_alias('A', 'B');
class_alias('A', 'B\C');


new A();
new A;

new B();
new B;

new B\C();
new B\C;


?>