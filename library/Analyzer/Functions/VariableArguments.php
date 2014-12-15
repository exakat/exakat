<?php

namespace Analyzer\Functions;

use Analyzer;

class VariableArguments extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath(array('\\func_get_arg', '\\func_get_args', '\\func_num_args'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
