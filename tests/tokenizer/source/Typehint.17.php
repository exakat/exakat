<?php
namespace B {
	function C(D $b) { }
}
namespace E {
	use function B\C;
	C([1,2,3]);
}





