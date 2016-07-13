<?php

use ArrayAccess as C;

class x implements ArrayAccess {}

class x2 implements \ArrayAccess {}

class x3 implements C, D {}

?>