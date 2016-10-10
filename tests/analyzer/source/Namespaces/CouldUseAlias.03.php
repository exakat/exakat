<?php

namespace A\B {
use a\b\c;

// This may be reduced with the above alias
new c\d();

// This too
new c\d\e\f();

// This yet again
new c();

// but no this one (needs the alias)
new b();

new \a\b\c();

}
?>