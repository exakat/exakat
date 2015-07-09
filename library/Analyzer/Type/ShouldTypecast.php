<?php

namespace Analyzer\Type;

use Analyzer;

class ShouldTypecast extends Analyzer\Analyzer {
    public function analyze() {
        $typeCasting = array('\\intval', '\\floatval', '\\strval', '\\boolval', '\\settype');
        
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
