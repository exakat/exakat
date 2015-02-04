<?php

namespace Analyzer\Structures;

use Analyzer;

class AlteringForeachWithoutReference extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }
    
    public function analyze() {
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'source')
             ->inIs('SOURCE')

             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('KEY')
             ->savePropertyAs('code', 'key')
             ->inIs('KEY')
             ->inIs('VALUE')

             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIs('VARIABLE')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->samePropertyAs('code', 'source')
             ->inIs('VARIABLE')

             ->outIs('INDEX')
             ->samePropertyAs('code', 'key')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
