<?php

namespace Analyzer\Classes;

use Analyzer;

class TestClass extends Analyzer\Analyzer {
    public function analyze() {
        $testClasses =  $this->loadIni('php_unittest.ini', 'classes');
        $testClasses =  $this->makeFullNSPath($testClasses);
    
        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->fullnspath($testClasses)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
