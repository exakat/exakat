<?php

namespace Analyzer\Php;

use Analyzer;

class UpperCaseFunction extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\IsExtFunction');
    }
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->analyzerIs('Analyzer\\Functions\\IsExtFunction')
             ->isNotLowerCase('code');
        $this->prepareQuery();
        
        // some of the keywords are lost anyway : implements, extends, as in foreach(), endforeach/while/for/* are lost in tokenizer (may be keep track of that) 

    }
}

?>