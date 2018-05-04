<?php

new \Exception();
$b = new \Exception('assignement');
throw (new \Exception('Parenthesis'));

class myRuntimeException extends \Exception {}
new \myRuntimeException();
$b = new \myRuntimeException('assignement');
throw (new \myRuntimeException('Parenthesis'));

?>