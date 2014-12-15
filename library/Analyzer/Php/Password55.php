<?php

namespace Analyzer\Php;

use Analyzer;

class Password55 extends Analyzer\Analyzer {
    public $phpVersion = '5.5+';
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath('\\crypt');
        $this->prepareQuery();
    }
}

?>
