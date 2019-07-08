<?php
function returnObject(Foo $f, Bar $b, $c): Bar {
	$b = new Bar();
	return $b;
	return new Bar();

	return 1;
	$a = new Foo();
	return $a;
	return new Foo();

	return $f;
	return $b;
	return $c;
}

?>