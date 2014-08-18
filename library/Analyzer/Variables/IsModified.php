<?php

namespace Analyzer\Variables;

use Analyzer;

class IsModified extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\Constructor');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS', 'DEFINE', 'CAST'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Variable")
             ->inIs(array('LEFT', 'VARIABLE'))
             ->atomIs(array('Assignation', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // catch
        $this->atomIs("Variable")
             ->inIs('VARIABLE')
             ->atomIs(array('Catch'))
             ->back('first');
        $this->prepareQuery();

        // arguments : reference variable in a custom function
        $this->atomIs("Variable")
             ->savePropertyAs('order', 'order')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoIn('METHOD') // possibly new too
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('order', 'order', true)
             ->is('reference', 'true')
             ->back('first');
        $this->prepareQuery();  

        // function/methods definition : all modified by incoming values
        // simple variable
        $this->atomIs("Function")
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable');
        $this->prepareQuery();  

        $this->atomIs("Function")
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('VARIABLE');
        $this->prepareQuery();  

        // PHP functions that are references
        $data = new \Data\Methods();
        
        $functions = $data->getFunctionsReferenceArgs();
        $references = array();
        
        foreach($functions as $function) {
            if (!isset($references[$function['position']])) {
                $references[$function['position']] = array('\\'.$function['function']);
            } else {
                $references[$function['position']][] = '\\'.$function['function'];
            }
        }
        
        foreach($references as $position => $functions) {
            $this->atomIs("Variable")
                 ->is('order', $position)
                 ->inIs('ARGUMENT')
                 ->inIs('ARGUMENTS')
                 ->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->fullnspath($functions)
                 ->back('first');
            $this->prepareQuery();
        }

        // Class constructors (__construct)
        $this->atomIs("Variable")
             ->savePropertyAs('order', 'order')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Functioncall')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->_as('method')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->back('method')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('order', 'order', true)
             ->is('reference', 'true')
             ->back('first');
        $this->prepareQuery(); 
    }
}

?>