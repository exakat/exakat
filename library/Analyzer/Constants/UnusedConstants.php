<?php

namespace Analyzer\Constants;

use Analyzer;

class UnusedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
      $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIsNot('T_VARIABLE')
             ->fullnspath("\\define")
             ->outIs('ARGUMENTS')
             ->orderIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('filter{ definition = it.fullnspath; g.idx("analyzers")[["analyzer":"Analyzer\\\\Constants\\\\ConstantUsage"]].out("ANALYZED").has("fullnspath", definition).any() }');
        $this->prepareQuery();
      }
}

?>