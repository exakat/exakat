<?php

namespace Analyzer\Structures;

use Analyzer;

class NoArrayUnique extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\array_unique')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
