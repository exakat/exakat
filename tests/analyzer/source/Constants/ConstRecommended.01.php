<?php 

namespace {
const ab = 1;
define('a', 2);
define('b', 2.1);
define('c', "3");
define('d', "true");
define('e', -1);
define('f', 'true');
define('g', ab);    // OK
define('h', a::C); // OK
define('i', true);
define('j', \ab);    // OK
define('k', \a\ab);    // OK
define('A', "a"."b"); 

define('B', "a $b c");
define('C', $a);

class a{ const C = 9; }

const CC = a::C;

echo a;
echo b;
echo h;
}

namespace a {
    const ab = 1;
}

?>