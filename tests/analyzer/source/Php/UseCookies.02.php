<?php

setcookie("TestCookie1", $value);
setcookie("TestCookie2", $value, time()+3600);  /* expire in 1 hour */
setcookie("TestCookie3", $value, time()+3600, "/~rasmus/", "example.com", 1);

setrawcookie("TestCookie4", $value);
setrawcookie("TestCookie5", $value, time()+3600);  /* expire in 1 hour */
setrawcookie("TestCookie6", $value, time()+3600, "/~rasmus/", "example.com", 1);

$a->setrawcookie();
A::setrawcookie();

?>