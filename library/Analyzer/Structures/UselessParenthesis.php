<?php

namespace Analyzer\Structures;

use Analyzer;

class UselessParenthesis extends Analyzer\Analyzer {
    // if ( ($condition) ) 
    public function analyze() {
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->outIs('CODE')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // while
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // $y = (1);
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Parenthesis');
        $this->prepareQuery();

        // ($y) == (1);
        $this->atomIs('Comparison')
             ->outIs(array('RIGHT', 'LEFT'))
             ->atomIs('Parenthesis');
        $this->prepareQuery();
    }
}

?>
