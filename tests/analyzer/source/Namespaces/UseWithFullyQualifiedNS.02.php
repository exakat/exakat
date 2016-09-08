<?php

use \Fully\Qualified\NS;
use \FullyQualifiedNs as Alias;

use Qualified\NS as NS2;
use QualifiedNs as Alias2;

class x {
use \Fully\Qualified\aClass\NS;
use \FullyQualifiedClassNs;

    use A, B {
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
        B::bigTalk as talk;
    }
}

trait t {
use \Fully\Qualified\aTrait\NS;
use \FullyQualifiedTraitNs;

    use A, \B {
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
        B::bigTalk as talk;
    }
}


?>