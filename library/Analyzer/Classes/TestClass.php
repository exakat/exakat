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

// second level of heritage
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();

// third level of heritage
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
