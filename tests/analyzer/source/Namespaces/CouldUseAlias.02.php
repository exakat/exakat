<?php

namespace A {
use a\b\c;

// This may be reduced with the above alias
new b\c\d();

// This too
new b\c\d\e\f();

// This yet again
new b\c();

// but no this one (needs the alias)
new b();

new \a\b\c();

}
?>