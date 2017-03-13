<?php

namespace A {
    use non\_trait\_use as bc;
    
    trait G {
        function H() {  }
    }
    
    trait B {
        function C() {  }
        function D() {  }
    }
}
    
namespace B {
    use non\_trait\_use as c;
    
    class E extends F {
        use A\G;
        use UndefinedTrait;
    }
    
    class H extends I {
        use A\B, A\G;
        
    }
}
?>