<?php

use a\b\{c, d, e, f, };

use a1\b1\{c1, d1, e1, f1};

trait t {
    use HelloWorld { sayHello as protected; 
                     B::smallTalk insteadof A;}

    use dd\d { c4 as protected; }
}
?>