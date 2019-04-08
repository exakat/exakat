<?php

namespace A\B {
use a\b\c;

// This may not be reduced with the above alias
new c\d();

// This too
new c\d\e\f();

// This yet again
new c();

// and not this one (needs the alias)
new b();

// the only reduction available
new \a\b\c();

}
?>