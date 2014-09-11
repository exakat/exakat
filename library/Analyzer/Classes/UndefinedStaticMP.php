<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedStaticMP extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\DefinedStaticMP');
    }
    
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs("Staticmethodcall")
             ->analyzerIsNot('Analyzer\\Classes\\DefinedStaticMP');
        $this->prepareQuery();

        // static::$property 1rst level
        $this->atomIs("Staticproperty")
             ->analyzerIsNot('Analyzer\\Classes\\DefinedStaticMP');
        $this->prepareQuery();
    }
}

?>