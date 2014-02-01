<?php

namespace Analyzer\Structures;

use Analyzer;

class ErrorReportingWithInteger extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->code('error_reporting', false)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();
    }
}

?>