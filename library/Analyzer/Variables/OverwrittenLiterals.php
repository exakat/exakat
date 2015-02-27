<?php

namespace Analyzer\Variables;

use Analyzer;

class OverwrittenLiterals extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }
    
    public function analyze() {
        $this->atomIs('Assignation')
             ->code('=')
             ->hasNoIn('INIT')
             ->outIs('RIGHT')
             ->atomIs(array('Integer', 'String', 'Real', 'Float'))
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->analyzerIsNot('self')
             ->_as('result')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->fetchContext()
             ->eachCounted('it.code + "/" + context.Function + "/" + context.Class + "/" + context.Namespace', 1, '>');
        $this->prepareQuery();
    }
}

?>
