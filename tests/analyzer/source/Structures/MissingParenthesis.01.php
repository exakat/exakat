<?php

$a = new Stdclass();

// Missing some parenthesis!!
if (!$a instanceof Stdclass) {
    print "Not\n";
} else {
    print "Is\n";
}

-$a + $b;
-3 + $b;
+$a + $b - c;
-$a2 + $b2 - c2;

-($a + $b) + $d;


?>