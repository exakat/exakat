<?php

namespace Analyzer\Php;

use Analyzer;

class GlobalWithoutSimpleVariable extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->tokenIs('T_DOLLAR')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
