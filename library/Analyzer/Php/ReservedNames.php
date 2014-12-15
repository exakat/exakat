<?php

namespace Analyzer\Php;

use Analyzer;

class ReservedNames extends Analyzer\Analyzer {

    public function analyze() {
        $reservedNames = $this->loadIni('php_keywords.ini');
        $reservedNames = $reservedNames['keyword'];

        // functions/methods names
        $this->atomIs('Function')
             ->outIs('NAME')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // classes
        $this->atomIs('Class')
             ->outIs('NAME')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // trait
        $this->atomIs('Trait')
             ->outIs('NAME')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // interface
        $this->atomIs('Interface')
             ->outIs('NAME')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // methodcall
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // property
        $this->atomIs('Property')
             ->outIs('METHOD')
             ->code($reservedNames)
             ->back('first');
        $this->prepareQuery();

        // variables
        $variablesReservedNames = array_map(function ($x) { return '$'.$x;}, $reservedNames);
        $this->atomIs('Variable')
             ->code($variablesReservedNames);
        $this->prepareQuery();
    }
}

?>
