<?php

namespace Analyzer\Spip;

use Analyzer;

class NonStandardDefine extends Analyzer\Analyzer {
    public function analyze() {
    //-* les define sont toutes majuscules et préfixé par _
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regexNot('noDelimiter', '^_[A-Z0-9_]+\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
