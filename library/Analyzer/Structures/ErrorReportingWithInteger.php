<?php

namespace Analyzer\Structures;

use Analyzer;

class ErrorReportingWithInteger extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('error_reporting', false)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->code('ini_set', false)
             ->outIs('ARGUMENTS')
             ->orderIs('ARGUMENT', 0)
             ->atomIs('String')
             ->is('noDelimiter', "'error_reporting'")
             ->inIs('ARGUMENT')
             ->orderIs('ARGUMENT', 1)
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();
    }
}

?>