<?php

namespace Analyzer\Structures;

use Analyzer;

class StrposCompare extends Analyzer\Analyzer {
    static public $operator = array('strpos', 'stripos', 'strrpos', 'strripos', 
                                    'strstr', 'stristr', 'file_get_contents',
                                    'fread', );
    
    public function analyze() {
        // if (.. == strpos(..)) {}
        $this->atomIs("Functioncall")
             ->_as('result')
             ->code(StrposCompare::$operator)
             ->inIs('RIGHT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('LEFT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..) == ..) {}
        $this->atomIs("Functioncall")
             ->_as('result')
             ->code(StrposCompare::$operator)
             ->inIs('LEFT')
             ->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs('RIGHT')
             ->code(array('0', '', 'null', 'false'))
             ->back('result');
        $this->prepareQuery();

        // if (strpos(..)) {}
        $this->atomIs("Functioncall")
             ->_as('result')
             ->code(StrposCompare::$operator)
             ->inIs('CODE')
             ->inIs('CONDITION')
             ->atomIs('Ifthen')
             ->back('result');
        $this->prepareQuery();

        // if ($x = strpos(..)) {}
        $this->atomIs("Functioncall")
             ->code(StrposCompare::$operator)
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
