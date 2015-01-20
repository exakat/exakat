<?php

namespace Analyzer\Security;

use Analyzer;

class parseUrlWithoutParameters extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\parse_str')
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', '1')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
