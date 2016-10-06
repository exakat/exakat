<?php

class EF extends D {
    use traitT; // This is a use for a trait

    function foo() {
        // This is a use for a closure
        return function ($a) use ($b) {};
    }

    use traitT2;
    use traitT3, traitT3;
    use traitT4, traitT5 { A::bigTalk insteadof B;}

}

?>
