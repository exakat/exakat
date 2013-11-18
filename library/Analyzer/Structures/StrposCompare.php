<?php

namespace Analyzer\Structures;

use Analyzer;

class StrposCompare extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->_as('result')
             ->code('strpos')
             ->in('RIGHT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->out('LEFT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->_as('result')
             ->code('strpos')
             ->in('LEFT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->out('RIGHT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->_as('result')
             ->code('strpos')
             ->in('CODE')
             ->in('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();
    }
}

?>