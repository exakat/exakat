<?php

namespace Analyzer\Constants;

use Analyzer;

class BadConstantnames extends Analyzer\Analyzer {
    public function analyze() {
        // with define
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define', false)
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', "'3'")
             ->regex('code', '^[\'\\"]__(.*)__[\'\\"]\\$');
        $this->prepareQuery();
        
        //with const
        $this->atomIs('Const')
             ->outIs('NAME')
             ->regex('code', '^[\'\\"]__(.*)__[\'\\"]\\$');
        $this->prepareQuery();
    }
}

?>
