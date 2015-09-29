<?php

namespace Analyzer\Php;

use Analyzer;

class GlobalWithoutSimpleVariable extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->tokenIs(array('T_DOLLAR'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
