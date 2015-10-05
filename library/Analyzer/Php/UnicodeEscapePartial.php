<?php

namespace Analyzer\Php;

use Analyzer;

class UnicodeEscapePartial extends Analyzer\Analyzer {
    protected $phpVersion = '7.0-';
    
    public function analyze() {
        // Normal string
        $this->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->regex('noDelimiter', '\\\\\\\\u\\\\{')
             ->back('first');
        $this->prepareQuery();

        // Here/NowDoc string
        $this->atomIs('Heredoc')
             ->outIs('CONTAINS')
             ->regex('noDelimiter', '\\\\\\\\u\\\\{')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
