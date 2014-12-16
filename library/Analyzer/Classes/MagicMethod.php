<?php

namespace Analyzer\Classes;

use Analyzer;

class MagicMethod extends Analyzer\Analyzer {
    
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini');
        $magicMethods = $magicMethods['magicMethod'];
        
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->atomInside('Function')
             ->outIs('NAME')
             ->code($magicMethods);
    }
}

?>
