<?php

namespace Analyzer\Security;

use Analyzer;

class SensitiveArgument extends Analyzer\Analyzer {
    public function analyze() {
        $unsafe = $this->loadIni('security_vulnerable_functions.ini');
        
        $positions = array(0, 1);
        
        foreach($positions as $position) {
            $functions = array_map(function ($class) { return '\\'.strtolower($class); }, $unsafe['functions'.$position]);

            // $_GET/_POST ... directly as argument of PHP functions
            $this->atomIs("Functioncall")
                 ->fullnspath($functions)
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position);
            $this->prepareQuery();
        }
    }
}

?>