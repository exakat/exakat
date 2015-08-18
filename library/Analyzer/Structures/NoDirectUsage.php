<?php

namespace Analyzer\Structures;

use Analyzer;

class NoDirectUsage extends Analyzer\Analyzer {
    public function analyze() {
        $functions = $this->loadIni('NoDirectUsage.ini', 'functions');
        
        // foreach(glob() as $x) {} 
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code($functions)
             ->back('first');
        $this->prepareQuery();

        // Direct call with a function without check
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code($functions)
             ->hasIn('ARGUMENT');
        $this->prepareQuery();

        // Direct usage in an operation +, *, **
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code($functions)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Addition', 'Multiplication', 'Power'));
        $this->prepareQuery();

    }
}

?>
