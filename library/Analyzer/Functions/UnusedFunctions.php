<?php

namespace Analyzer\Functions;

use Analyzer;

class UnusedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\UsedFunctions');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->analyzerIsNot('Analyzer\\Functions\\UsedFunctions');
        $this->prepareQuery();
    }
}

?>