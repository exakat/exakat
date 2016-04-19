<?php

class a extends \RuntimeException {}
class b extends a {}
class c extends b {}
class ad extends \RuntimeException {}
class ae extends \Exception {}

//throw new ControllerFrozenException();

try {

} catch (ad $a) {
} catch (RuntimeException $e) {}

?>