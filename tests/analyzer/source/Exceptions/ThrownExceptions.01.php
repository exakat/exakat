<?php
throw new Exception();
throw new Exception2();
throw $x;
throw $x = new Exception();

new Exception3();

$a->throw($b);
?>  