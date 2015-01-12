<?php

namespace Analyzer\Structures;

use Analyzer;

class VardumpUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\var_dump', '\\print_r'))
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 1)
             ->codeIsNot("true")
             ->back('first');
        $this->prepareQuery();

        // var_dump($x, 1) will not print directly, so it's OK 
        // (well, we need to check if the result string is not printed now...)
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\var_dump', '\\print_r'))
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();
        
//         call_user_func_array('var_dump', )
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\call_user_func_array', '\\call_user_func'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->noDelimiter(array('print_r', 'var_dump'))
             ->back('first');
        $this->prepareQuery();

    }
}

?>
