<?php

namespace Analyzer\Structures;

use Analyzer;

class StrposCompare extends Analyzer\Analyzer {

    function analyze() {
        // if (.. == strpos(..)) {}
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

        // if (strpos(..) == ..) {}
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

        // if (strpos(..)) {}
        $this->atomIs("Functioncall")
             ->_as('result')
             ->code('strpos')
             ->in('CODE')
             ->in('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();

        // if ($x = strpos(..)) {}
        $this->atomIs("Functioncall")
             ->code('strpos')
             ->in('RIGHT')
             ->atomIs('Assignation')
             ->_as('result')
             ->in('CODE')
             ->in('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();
    }
}

?>