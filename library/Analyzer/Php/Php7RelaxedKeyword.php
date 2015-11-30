<?php

namespace Analyzer\Php;

use Analyzer;

class Php7RelaxedKeyword extends Analyzer\Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
        $keywords = $this->loadIni('php7_relaxed_keyword.ini', 'keywords');
        
        // Method names
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code($keywords)
             ->inIs('NAME');
        $this->prepareQuery();

        // Constant names
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Const')
             ->outIs('CONST')
             ->outIs('NAME')
             ->code($keywords)
             ->inIs('NAME');
        $this->prepareQuery();

        // Property names
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->outIs('CONST')
             ->outIs('NAME')
             ->code($keywords)
             ->inIs('NAME');
        $this->prepareQuery();

        // Static Constant
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->code($keywords);
        $this->prepareQuery();

        // Static Property
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->code($keywords);
        $this->prepareQuery();

        // Static Methodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->code($keywords);
        $this->prepareQuery();

        // Methodcall not static
        $this->atomIs('Functioncall')
             ->tokenIs('T_STRING')
             ->hasIn('METHOD')
             ->code($keywords);
        $this->prepareQuery();
    }
}

?>
