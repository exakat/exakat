<?php

namespace {
    use A\B as C2;
    use C2\D as E; // This is actually \A\B\D
    
    new E();
}

namespace B {
    use C\D as E; // This is actually \C\D
    use A\B as C;  
    
    new E();
}

namespace A\B {
    class D {}
}

?>