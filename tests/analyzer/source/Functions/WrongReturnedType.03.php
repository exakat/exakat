<?php

interface FooInterface{}
class interfaced implements FooInterface{}

use Foo as FooAlias;
use Bar as BarAlias;

class Foo{}
class FooFoo extends Foo {}

class Bar{}
class BarBar extends bar {}

function returnChild(): Foo {
	return new Foo();
	return new FooFoo();
	return new Unknown();
}

function returnInterface(FooInterface $a, interfaced $b): FooInterface {
	return new Foo();
	return new interfaced();

	return $a;
	return $b;
}

function returnUse(): Foo {
	return new FooAlias();
	
	return new Bar;
}

function returnUse2(): FooAlias {
	return new Foo();
	
	return \Bar;
}

?>