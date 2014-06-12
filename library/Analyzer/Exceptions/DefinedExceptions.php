<?php

namespace Analyzer\Exceptions;

use Analyzer;

class DefinedExceptions extends Analyzer\Analyzer {
    public function analyze() {
        // first level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->fullnspath('\exception')
             ->back('first');
        $this->prepareQuery();

        // second level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs("Analyzer\\Exceptions\\DefinedExceptions")
             ->back('first');
        $this->prepareQuery();

        // third level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs("Analyzer\\Exceptions\\DefinedExceptions")
             ->back('first');
        $this->prepareQuery();

        // fourth level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs("Analyzer\\Exceptions\\DefinedExceptions")
             ->back('first');
        $this->prepareQuery();

        // fifth level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->classDefinition()
             ->analyzerIs("Analyzer\\Exceptions\\DefinedExceptions")
             ->back('first');
        $this->prepareQuery();
    }
}

?>