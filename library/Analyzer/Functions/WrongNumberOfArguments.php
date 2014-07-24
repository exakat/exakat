<?php

namespace Analyzer\Functions;

use Analyzer;

class WrongNumberOfArguments extends Analyzer\Analyzer {
    
    public function analyze() {
        $data = new \Data\Methods();
        
        $functions = $data->getFunctionsArgsInterval();
        $args_mins = array();
        $args_max = array();
        
        foreach($functions as $function) {
            if ($function['args_min'] > 0) {
                $args_mins[$function['args_min']][] = '\\'.$function['name'];
            }
            $args_maxs[$function['args_max']][] = '\\'.$function['name'];
        }
        
        foreach($args_mins as $nb => $f) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->fullnspath($f)
                 ->isLess('args_count', $nb);
            $this->prepareQuery();
        }

        foreach($args_maxs as $nb => $f) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->fullnspath($f)
                 ->isMore('args_count', $nb);
            $this->prepareQuery();
        }

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->isLess('args_max', 'args_count')
             ->back('first');
        $this->prepareQuery();
    }
}

?>