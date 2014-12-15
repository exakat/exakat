<?php

namespace Analyzer\Classes;

use Analyzer;

class ThisIsNotForStatic extends Analyzer\Analyzer {

    public function analyze() {
        // Check into Class
        $this->atomIs("Variable")
             ->code('$this')
             ->goToFunction()
             ->_as('result')
             ->hasNoOut('STATIC')
             ->goToClassTrait()
             ->back('result')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
