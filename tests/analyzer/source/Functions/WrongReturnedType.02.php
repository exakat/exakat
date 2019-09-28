<?php
function returnObject(Foo $f, Bar $b2, $c): Bar {
	$b = new Bar();
	return $b;
	return new Bar();

	return 1;
	$a = new Foo();
	return $a;
	return new Foo();

	return $f;
	return $b2;
	return $c;
}

?>