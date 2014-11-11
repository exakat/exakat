<?php

namespace A {
    class AException extends \Exception {}
    class ANotException extends Exception {}
}

namespace B {
    use A\AException;
    use A\AException as C;
    use A\AException as D, A\AException as E, A\AException as F;

    use A\ANotException;
    use A\ANotException as G;
    
    class B extends Aexception {}
    class BC extends C {}
    class BD extends D {}
    class BE extends E {}
    class BF extends F {}
    
    class BG extends G {}
    class BH extends ANotException {}
    
}

?>