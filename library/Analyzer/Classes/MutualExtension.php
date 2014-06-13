<?php

namespace Analyzer\Classes;

use Analyzer;

class MutualExtension extends Analyzer\Analyzer {
    public function analyze() {
        // A -> B 
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\MutualExtension')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->samePropertyAs('fullnspath', 'fullnspath')
             ->back('first');
        $this->prepareQuery();

        // A -> B -> C (2 levels)
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\MutualExtension')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\MutualExtension')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->samePropertyAs('fullnspath', 'fullnspath')
             ->back('first');
        $this->prepareQuery();
    }
}

?>