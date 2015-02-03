<?php

namespace Analyzer\Exceptions;

use Analyzer;

class Unthrown extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Exceptions\\DefinedExceptions');
    }
    
    public function analyze() {
        $this->atomIs('Class')
             ->analyzerIs('Analyzer\\Exceptions\\DefinedExceptions')
             ->savePropertyAs('fullnspath', 'path')
             ->raw('filter{ g.idx("atoms")[["atom":"Throw"]].out("THROW").out("NEW").has("fullnspath", path).any() == false}')
//             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>
