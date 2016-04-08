<?php

class thrownWithThrow extends \Exception {}
class thrownWithNew extends \Exception {}
class notAnException {}

throw new thrownWithThrow();
$a = new thrownWithNew();
throw $a;

new notAnException();
throw new notAnException();

?>