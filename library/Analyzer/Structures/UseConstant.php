<?php

namespace Analyzer\Structures;

use Analyzer;

class UseConstant extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('php_version', 'php_sapi'))
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('fopen')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->noDelimiter(array('php://stdin', 'php://stdout', 'php://stderr'))
             ->back('first');
        $this->prepareQuery();
        
        // dirname(__FILE__) => __DIR__
        $this->atomFunctionIs('dirname')
             ->outIs('ARGUMENTS')
             ->outIS('ARGUMENT')
             ->atomIs('Magicconstant')
             ->code('__FILE__')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
