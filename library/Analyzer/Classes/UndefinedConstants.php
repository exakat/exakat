<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\DefinedConstants');
    }
    
    public function analyze() {
        $this->atomIs('Staticconstant')
             ->analyzerIsNot('Analyzer\\Classes\\DefinedConstants');
        $this->prepareQuery();
    }
}

?>
