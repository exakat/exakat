<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedClasses extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass',
                     'Analyzer\\Classes\\IsVendor');
    }
    
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
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