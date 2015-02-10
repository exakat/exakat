<?php

namespace Analyzer\Functions;

use Analyzer;

class ShouldUseConstants extends Analyzer\Analyzer {
    public function analyze() {
        $functions = $this->loadIni('constant_usage.ini');
        
        $positions = array(0, 1, 2, 3, /*4, 5,*/ 6);
        foreach($positions as $position) {
            $this->atomFunctionIs($functions['functions' . $position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIsNot(array('Logical', 'Variable', 'Array', 'Property', 'Identifier', 'Nsname', 'Staticproperty', 'Staticconstant', 'Staticmethodcall', 'Methodcall'))
                 ->back('first');
            $this->prepareQuery();

            $this->atomFunctionIs($functions['functions' . $position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Logical') 
                 ->raw('filter{ it.out.loop(1){!(it.object.atom in ["Identifier", "Nsname"])}{!(it.object.atom in ["Identifier", "Nsname", "Parenthesis", "Logical"])}.any()}') 
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
