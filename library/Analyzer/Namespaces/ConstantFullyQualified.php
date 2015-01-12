<?php

namespace Analyzer\Namespaces;

use Analyzer;

class ConstantFullyQualified extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->regex('noDelimiter', '^(\\\\\\\\)')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
