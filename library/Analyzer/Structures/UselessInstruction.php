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
                            'Constant', 'String', 'Instanceof', 'Bitshift', 'Logical', 'Comparison', 'Null'))
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
             ->atomIs('PostPlusPlus')
             ->back('first');
        $this->prepareQuery();
    }
}

?>