<?php

namespace Analyzer\Structures;

use Analyzer;

class ErrorReportingWithInteger extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code('error_reporting', false)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code('ini_set', false)
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIsNot('T_QUOTE')
             ->noDelimiter('error_reporting')
             ->inIs('ARGUMENT')
             ->rankIs('ARGUMENT', 1)
             ->atomIs('Integer')
             ->codeIsNot(0)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
