<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstRecommended extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->_as('args')
             ->outIs('ARGUMENT')
             ->is('order', 0)
             ->atomIs('String')
             ->back('args')
             ->outIs('ARGUMENT')
             ->is('order', 1)
             ->atomIs(array('String', 'Float', 'Integer', 'Boolean', 'Staticconstant'))
             ->hasNoOut('CONTAIN')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->_as('args')
             ->outIs('ARGUMENT')
             ->is('order', 0)
             ->atomIs('String')
             ->back('args')
             ->outIs('ARGUMENT')
             ->is('order', 1)
             ->atomIs(array('Identifier', 'Nsname'))
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->back('first');
        $this->prepareQuery();
    }
}

?>