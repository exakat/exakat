<?php

namespace A\B;

trait usedT { }
trait usedT2 { }
trait usedT3 { }

trait unused {}

namespace A\C;

class usingT {
    use \A\B\usedT, \A\B\usedT2;
    use \A\B\usedT3 ;
}

class usingOtherTrait {
    use \A\B\unknownT;
}

class noTrait {
}

?>