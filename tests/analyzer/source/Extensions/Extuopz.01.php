<?php
class Foo {
	public function bar(int $arg) : int {
		return $arg;
	}
}
var_dump(uopz_set_return(Foo::class, "bar", true));
$foo = new Foo();
var_dump($foo->bar(1));
uopz_set_return(Foo::class, "bar", function(int $arg) : int {
	return $arg * 2;
}, true);
var_dump($foo->bar(2));
try {
	uopz_set_return(Foo::class, "nope", 1);
} catch(Throwable $t) {
	var_dump($t->getMessage());
}
class Bar extends Foo {}
try {
	uopz_set_return(Bar::class, "bar", null);
} catch (Throwable $t) {
	var_dump($t->getMessage());
}

	uopz_set_something(Bar::class, "bar", null);

?>