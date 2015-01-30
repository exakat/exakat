<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedMethods extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\UsedMethods',
                     'Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        // Methods definitions
        $this->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedMethods')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->codeIsNot($magicMethods)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
