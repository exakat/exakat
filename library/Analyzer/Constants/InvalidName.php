<?php

namespace Analyzer\Constants;

use Analyzer;

class InvalidName extends Analyzer\Analyzer {
    public function analyze() {
        // case-sensitive constants
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath("\\define")
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->regexNot('noDelimiter', '^[a-zA-Z_\\\\u007f-\\\\u00ff][a-zA-Z0-9_\\\\u007f-\\\\u00ff]*\\$');
        $this->prepareQuery();
    }
}

?>
