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
             ->rankIs('ARGUMENT', 0)
             ->atomIs('String')
             ->noDelimiter("error_reporting")
             ->inIs('ARGUMENT')
             ->rankIs('ARGUMENT', 1)
             ->atomIs('Integer')
             ->codeIsNot(0)
             ->back('first');
        $this->prepareQuery();
    }
}

?>