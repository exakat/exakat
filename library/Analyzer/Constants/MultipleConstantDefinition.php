<?php

namespace Analyzer\Constants;

use Analyzer;

class MultipleConstantDefinition extends Analyzer\Analyzer {
    public function analyze() {
        // case-sensitive constants
        $this->atomIs("Functioncall")
             ->code("define")
             ->outIs('ARGUMENTS')
             ->orderIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('groupCount(m){it.noDelimiter}.aggregate().filter{m[it.noDelimiter] > 1}');
        $this->prepareQuery();

        // case-insensitive constants
        $this->atomIs("Functioncall")
             ->code("define")
             ->outIs('ARGUMENTS')
             ->orderIs('ARGUMENT', 2)
             ->atomIs('Boolean')
             ->code('true', true)
             ->inIs('ARGUMENT')
             ->orderIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('groupCount(m){it.noDelimiter.toLowerCase()}.aggregate().filter{m[it.noDelimiter.toLowerCase()] > 1}');
        $this->prepareQuery();
    }
}

?>