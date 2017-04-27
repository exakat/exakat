<?php

use a\b\c;
use a\b\d;
use x\c\d\e as f;

// alias/extra : Nope
new \a\b\d\e();

// This should be made alias
new \a\b\c();

// Not an existing alias
new \a\b\w();

new \A\B\DD(new \A\B\D( ), $o->m( ) . '/d');


?>