<?php

include ('def.php');

bar($a, $b);
bar($a, foo(1));
bar($a, foo(1), foo(2));
bar($a[1], $a[2]);
bar(A::$a, A::$b);

bar(null, null);
bar(foo(), foo());
bar(foo(), $b);
bar(foo(), $b, $c);
bar($a->boo(), $a->boo());

// test on methods is not possible ATM

foo::bar($a, $b);
foo::bar($a[1], $a[2]);
foo::bar(A::$a, A::$b);

foo::bar(null, null);
foo::bar(foo(), foo());
foo::bar($a->boo(), $a->boo());

// Test on PHP native functions
reset(foo());
getmxrr(foo(), $a); // OK
getmxrr($a, foo()); // KO

rasat(foo()); // Not native, no definition : Just ignore
NoClass::rasat(foo()); // Not native, no definition : Just ignore

?>