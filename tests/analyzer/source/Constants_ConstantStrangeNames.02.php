<?php

namespace {
define('a', 1);
define('\b', 2);
define('c\d', 3);
define('\e\f', 4);
define('\g\h\i', 5);
define('g2\h2\i2', 8);
define('\j\\\\k\l', 6);
define('\j\k\l\\', 7);

print_r(get_defined_constants());
}

namespace c {
    print d;
}
?>