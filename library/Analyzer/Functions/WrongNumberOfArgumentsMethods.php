<?php

namespace Analyzer\Functions;

use Analyzer;

class WrongNumberOfArgumentsMethods extends Analyzer\Analyzer {
    
    public function analyze() {
        $data = new \Data\Methods();
        
        $methods = $data->getMethodsArgsInterval();
        $args_mins = array();
        $args_max = array();
        
        // classes are ignored at that point. No way yet to refine this.
        foreach($methods as $method) {
            if ($method['args_min'] > 0) {
                $args_mins[$method['args_min']][] = $method['name'];
            }
            $args_maxs[$method['args_max']][] = $method['name'];
        }
        
        // case for methods
        foreach($args_mins as $nb => $f) {
            $this->atomIs(array("Methodcall", 'Staticmethodcall'))
                 ->outIs('METHOD')
                 ->code($f)
                 ->isLess('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        foreach($args_maxs as $nb => $f) {
            $this->atomIs(array("Methodcall", 'Staticmethodcall'))
                 ->outIs('METHOD')
                 ->code($f)
                 ->isMore('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>