<?php

namespace Analyzer\Functions;

use Analyzer;

class DeepDefinitions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\Closures');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Functions\\Closures')
             ->goToFunction()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->goToFunction()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Interface')
             ->goToFunction()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Trait')
             ->goToFunction()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Const')
             ->goToFunction()
             ->back('first');
        $this->prepareQuery();

        // define ? Constants are OK.
    }
}

?>
