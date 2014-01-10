<?php

namespace Analyzer\Structures;

use Analyzer;

class ErrorReportingWithInteger extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->code('error_reporting', false)
             ->out('ARGUMENTS')
             ->out('ARGUMENT')
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();
    }
}

?>