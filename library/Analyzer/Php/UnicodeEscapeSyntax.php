<?php

namespace Analyzer\Php;

use Analyzer;

class UnicodeEscapeSyntax extends Analyzer\Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
        $this->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->regex('noDelimiter', '\\\\\\\\u\\\\{[a-fA-F0-9]+\\\\}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
