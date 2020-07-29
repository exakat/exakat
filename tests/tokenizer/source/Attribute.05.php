<?php

@@A1(1)
class Foo
{
    @@A1(2)
    public const FOO = 'foo';

    @@A1(3)
    public $x;

    @@A1(4)
    private function foo(@@A1(5) $a, @@A1(6) $b) {} 
}

$object = new @@A1(7) class () { };

@@A1(8)
function f1() { }

$f2 = @@A1(9) function () { };

$f3 = @@A1(10) fn () => 1;

$ref = new \ReflectionClass(Foo::class);

$sources = [
    $ref,
    $ref->getReflectionConstant('FOO'),
    $ref->getProperty('x'),
    $ref->getMethod('foo'),
    $ref->getMethod('foo')->getParameters()[0],
    $ref->getMethod('foo')->getParameters()[1],
    new \ReflectionObject($object),
    new \ReflectionFunction('f1'),
    new \ReflectionFunction($f2),
    new \ReflectionFunction($f3)
];

foreach ($sources as $r) {
	$attr = $r->getAttributes();
	var_dump(get_class($r), count($attr));
	
    foreach ($attr as $a) {
        var_dump($a->getName(), $a->getArguments());
    }
    
    echo "\n";
}

?>