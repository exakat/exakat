<?php
$$foo['bar']['baz'];
$foo->$bar['baz'];
Foo::$bar['baz']; // OK

Foo::bar['baz']; // OK (constants couldn't be arrays until PHP 7)

$foo->$bar['baz2']();
Foo::$bar['baz']();

class a { static $b = ['c' => 'd'];}
$c = 'c';
print a::$b[$c];
?>