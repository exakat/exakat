<?php

namespace  {
use a\b\c;

// This may be reduced with the above alias
new a\b\c\d();

// This too
new a\b\c\d\e\f();

// This yet again
new a\b\c();

// but no this one (needs the alias)
new a\b();
}
?>