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
                 ->isLess('count', $nb);
            $this->prepareQuery();
        }

        foreach($args_maxs as $nb => $f) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->fullnspath($f)
                 ->isMore('count', $nb);
            $this->prepareQuery();
        }
    }
}

?>