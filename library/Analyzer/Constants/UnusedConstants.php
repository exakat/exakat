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
             ->rankIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('filter{ name = it.noDelimiter; g.idx("analyzers")[["analyzer":"Analyzer\\\\Constants\\\\ConstantUsage"]].out("ANALYZED").has("code", name).any() == false }');
        $this->prepareQuery();
      }
}

?>