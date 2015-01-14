<?php

namespace Analyzer\Functions;

use Analyzer;

class ShouldBeTypehinted extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name')
             ;
        $this->prepareQuery();

        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name');
        $this->prepareQuery();
    }
}

?>
