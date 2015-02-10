<?php

namespace Analyzer\Classes;

use Analyzer;

class toStringPss extends Analyzer\Analyzer {
    public function analyze() {
        $methods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code($methods)
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code($methods)
             ->inIs('NAME')
             ->hasNoOut('PUBLIC')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
