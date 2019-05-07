<?php

namespace A\B\C\D { 
    function foo() {}
}

namespace A\B { 
    C\D\foo();
    D\foo();
}

?>