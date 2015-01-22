<?php

namespace Analyzer\Structures;

use Analyzer;

class NestedLoops extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('For')
             ->outIs('BLOCK')
             ->atomInside(array('For' ,'Foreach', 'While', 'Dowhile'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Foreach')
             ->outIs('BLOCK')
             ->atomInside(array('For' ,'Foreach', 'While', 'Dowhile'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Dowhile')
             ->outIs('BLOCK')
             ->atomInside(array('For' ,'Foreach', 'While', 'Dowhile'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('While')
             ->outIs('BLOCK')
             ->atomInside(array('For' ,'Foreach', 'While', 'Dowhile'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
