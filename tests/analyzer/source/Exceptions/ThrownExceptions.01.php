<?php

class myException extends Exception {}
class mySubException extends myException {}

throw new myException();
throw new mySubException();
throw new unknownException();
throw new runtimeException();

throw $x;
throw $x = new Exception();


?>  