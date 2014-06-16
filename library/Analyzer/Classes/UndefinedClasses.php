<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedClasses extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->outIs('RIGHT')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();
    }
}

?>