<?php

namespace A\B;

trait usedT { }

trait unusedT {}

namespace A\C;

class usingT {
    use \A\B\usedT;
}

class usingOtherTrait {
    use \A\B\unknownT;
}

class noTrait {
}

?>