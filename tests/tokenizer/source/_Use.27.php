<?php 

class B {

	use C {
		D as E;
	}
	use F, G {
		D as E;
	}
	use F2\F3, G2\G3\G4 {
		D as E;
	}

}

{ $a++; }

?>