<?php

namespace Analyzer\Variables;

use Analyzer;

class IsModified extends Analyzer\Analyzer {
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
                 ->analyzerIs('Analyzer\\Variables\\Variablenames')
                 ->inIs('ARGUMENT')
                 ->inIs('ARGUMENTS')
                 ->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->fullnspath($functions)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>