<?php

namespace Analyzer\Php;

use Analyzer;

class Php7RelaxedKeyword extends Analyzer\Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
        $keywords = $this->loadIni('php7_relaxed_keyword.ini', 'keywords');
        
        //////////////////////////////////////////////////////////////////////
        // Definitions in a class                                           //
        //////////////////////////////////////////////////////////////////////
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

        //////////////////////////////////////////////////////////////////////
        // Static usage                                                     //
        //////////////////////////////////////////////////////////////////////
        // Static Constant
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        // Static Methodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        // Static Property
        $keywordsVariables = array_map(function ($x) { return '$'.$x; }, $keywords);
        $this->atomIs('Staticproperty')
             ->outIs('PROPERTY')
             ->code($keywordsVariables)
             ->back('first');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////////
        // Static usage                                                     //
        //////////////////////////////////////////////////////////////////////
        // Methodcall 
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        // Property
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
