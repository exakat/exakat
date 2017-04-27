<?php

namespace A {
use a\b\c;
use a\b\d;

// alias/extra : Nope
new \a\b\d\e();

// This should be made alias
new \a\b\c();

// Not an existing alias
new \a\b\w();

}
?>