<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedClasses extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass');
    }
    
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->analyzerIsNot('Analyzer\\Interfaces\\IsExtInterface')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();
    }
}

?>