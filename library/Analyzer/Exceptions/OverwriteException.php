<?php

namespace Analyzer\Exceptions;

use Analyzer;

class OverwriteException extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }

    public function analyze() {
        $this->atomIs("Try")
             ->outIs('CATCH')
             ->_as('result')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'exception')
             ->inIs('VARIABLE')
             ->outIs('CODE')
             ->atomInside('Variable')
             ->samePropertyAs('code', 'exception')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
