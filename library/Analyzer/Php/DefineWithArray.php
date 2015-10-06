<?php

namespace Analyzer\Php;

use Analyzer;

class DefineWithArray extends Analyzer\Analyzer {
    protected $phpVersion = '7.0+';

    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();

        // define('a', $var) with $var is an array
    }
}

?>
