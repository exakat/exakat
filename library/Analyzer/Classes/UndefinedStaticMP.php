<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedStaticMP extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\DefinedStaticMP',
                     'Analyzer\\Classes\\IsVendor');
    }
    
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('self', 'static'))
             ->back('first')
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->analyzerIsNot('Analyzer\\Classes\\DefinedStaticMP');
        $this->prepareQuery();

        // static::$property 1rst level
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code(array('self', 'static'))
             ->back('first')
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->analyzerIsNot('Analyzer\\Classes\\DefinedStaticMP');
        $this->prepareQuery();
    }
}

?>
