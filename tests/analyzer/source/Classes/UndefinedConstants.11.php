<?php

namespace Exakat\Extensions{
    abstract class B {
        const VERSION_ALL_B     = 'none';
    }
    
    abstract class A extends B {

        const VERSION_ALL     = 'none';
        
        const NO_DEPENDENCIES = array();
        
        public function __construct() {}
    
        public function dependsOnExtensions() : array {
            return array();
        }
    
    }
}

namespace Exakat\Autoload{
    use Exakat\Extensions\A;

    echo  A::VERSION_ALL;
    echo  A::VERSION_ALL2;
    echo  A::VERSION_ALL_B;
    echo  A::VERSION_ALL_B2;
}
?>