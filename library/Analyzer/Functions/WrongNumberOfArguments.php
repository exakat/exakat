<?php

namespace Analyzer\Functions;

use Analyzer;

class WrongNumberOfArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\VariableArguments');
    }
    
    public function analyze() {
        // this is for functions defined within PHP
        $data = new \Data\Methods();
        
        $functions = $data->getFunctionsArgsInterval();
        $argsMins = array();
        $argsMaxs = array();
        
        foreach($functions as $function) {
            if ($function['args_min'] > 0) {
                $argsMins[$function['args_min']][] = '\\'.$function['name'];
            }
            $argsMaxs[$function['args_max']][] = '\\'.$function['name'];
        }
        
        foreach($argsMins as $nb => $f) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($f)
                 ->isLess('args_count', $nb);
            $this->prepareQuery();
        }

        foreach($argsMaxs as $nb => $f) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($f)
                 ->isMore('args_count', $nb);
            $this->prepareQuery();
        }

        // this is for custom functions 
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIsNot('Analyzer\\Functions\\VariableArguments')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIsNot('Analyzer\\Functions\\VariableArguments')
             ->isLess('args_max', 'args_count')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
