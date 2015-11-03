<?php
mcrypt_create_iv(1);
mcrypt_create_iv(1,2);
mcrypt_create_iv(1,2,3);

$x->mcrypt_create_iv(4); // this is a method
Stdclass::mcrypt_create_iv(5); // this is a class method

?>