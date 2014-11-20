<?php

namespace Analyzer\Structures;

use Analyzer;

class UselessInstruction extends Analyzer\Analyzer {
    public function analyze() {
        // Structures that should be put somewhere, and never left alone
        $this->atomIs("Sequence")
             ->outIs('ELEMENT')
             ->atomIs(array('Array', 'Addition', 'Multiplication', 'Property', 'Staticproperty', 'Boolean',
                            'Magicconstant', 'Staticconstant', 'Integer', 'Float', 'Sign', 'Nsname',
                            'Identifier', 'String', 'Instanceof', 'Bitshift', 'Logical', 'Comparison', 'Null'))
             ->noAtomInside('Functioncall');
        $this->prepareQuery();
        
        // -$x = 3
        $this->atomIs("Assignation")
             ->outIs('LEFT')
             ->atomIs('Sign');
        $this->prepareQuery();

        // closures that are not assigned to something (argument or variable)
        $this->atomIs("Sequence")
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->is('lambda', "true");
        $this->prepareQuery();

        // return $a++;
        $this->atomIs("Return")
             ->outIs('RETURN')
             ->atomIs('Postplusplus')
             ->back('first');
        $this->prepareQuery();

        // array_merge($a);
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\array_merge', '\\array_merge_recursive', '\\array_replace'))
             ->atomIs('Postplusplus')
             ->back('first');
        $this->prepareQuery();
    }
}

?>