<?php

use traversable as z;


interface i extends Traversable {
}

class x implements i {
}

class y implements \traversable {
}

class a implements z {
}

abstract class b implements \Iterator {}

abstract class b2 implements \Iterator, traversable {}

?>