<?php

namespace Analyzer\Exceptions;

use Analyzer;

class DefinedExceptions extends Analyzer\Analyzer {
    public function analyze() {
        // first level
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->code('Exception')
             ->back('first');
        $this->prepareQuery();

        // second level
        $this->atomIs("Class")
             ->inIs('DEFINES')
             ->analyzerIs("Analyzer\\Exceptions\\DefinedExceptions")
             ->back('first');
        $this->prepareQuery();
//        $this->printQuery();
    }
}

?>