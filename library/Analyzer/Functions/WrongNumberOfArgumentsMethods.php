<?php

namespace Analyzer\Functions;

use Analyzer;

class WrongNumberOfArgumentsMethods extends Analyzer\Analyzer {
    
    public function analyze() {
        $data = new \Data\Methods();
        
        $methods = $data->getMethodsArgsInterval();
        $argsMins = array();
        $argsMaxs = array();
        
        // classes are ignored at that point. No way yet to refine this.
        foreach($methods as $method) {
            if ($method['args_min'] > 0) {
                $argsMins[$method['args_min']][] = $method['name'];
            }
            $argsMaxs[$method['args_max']][] = $method['name'];
        }
        
        // case for methods
        foreach($argsMins as $nb => $f) {
            $this->atomIs(array("Methodcall", 'Staticmethodcall'))
                 ->outIs('METHOD')
                 ->code($f)
                 ->isLess('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        foreach($argsMaxs as $nb => $f) {
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
