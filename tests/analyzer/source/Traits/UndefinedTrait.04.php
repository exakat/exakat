<?php

use useTrait;
use const useConst;

trait t {}

class x1 {
    use undefined;
    use t;
    use useTrait;
    use useConst;
    use Stubs\stubTraits;
}

?>
