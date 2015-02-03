<?php

namespace Analyzer\Structures;

use Analyzer;

class UselessGlobal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnceByContext',
                     'Analyzer\\Structures\\UnusedGlobal');
    }
    
    public function analyze() {
        // Global are unused if used only once
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->analyzerIsNot('Analyzer\\Structures\\UnusedGlobal')
             ->savePropertyAs('code', 'variable')
            // search in $GLOBALS
             ->raw('filter{ g.idx("atoms")[["atom":"Variable"]].has("code", "\$GLOBALS").in("VARIABLE").has("atom", "Array").out("INDEX").has("atom", "String").has("noDelimiter", variable.substring(1, variable.size())).any() == false }')
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->code('$GLOBALS')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->atomIs('String')
             ->raw('filter{ index = it.noDelimiter; g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("atom", "Variable").filter{ it.code ==  "\\$" + index}.any() == false}')
             ->inIs('INDEX')
             ->eachCounted('it.fullcode', 1);
        $this->prepareQuery();

        // $_POST and co are not needed as super globals
        $superglobals = $this->loadIni('php_superglobals', 'superglobal');        
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->code($superglobals);
        $this->prepareQuery();
        
        // used only once
        
        // written only
    }
}

?>
