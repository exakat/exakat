<?php

namespace  {
use a\b\c;
use a\b\d;

// alias/extra : Nope
new d\d();

// This should be made alias
new a\b\c\d\e\f();

// This is already an alias
new a\b\c();

}
?>