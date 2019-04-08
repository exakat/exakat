<?php

use A\B\C\D;
use A\B\C;

new D;
new C;
new C\E; // already using the prefix
new C\D; // may be upgraded to A\B\C\D
new A\B\C;

?>