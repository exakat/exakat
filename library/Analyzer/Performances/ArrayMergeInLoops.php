<?php

namespace Analyzer\Performances;

use Analyzer;

class ArrayMergeInLoops extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('For')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\array_merge')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Foreach')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\array_merge')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('While')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\array_merge')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('DoWhile')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\array_merge')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
