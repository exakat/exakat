<?php

namespace Analyzer\Structures;

use Analyzer;

class StrposCompare extends Analyzer\Analyzer {
    public function analyze() {
        $operator = $this->loadIni('php_may_return_boolean_or_zero.ini');
        $operator = $operator['functions'];
        
        // if (.. == strpos(..)) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('RIGHT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('LEFT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..) == ..) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('LEFT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('RIGHT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..)) {}
        $this->atomIs('Functioncall')
             ->_as('result')
             ->code($operator)
             ->inIs('CODE')
             ->inIs('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();

        // if ($x = strpos(..)) {}
        $this->atomIs('Functioncall')
             ->code($operator)
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->_as('result')
             ->inIs('CODE')
             ->inIs('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
