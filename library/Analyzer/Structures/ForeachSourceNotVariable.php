<?php

namespace Analyzer\Structures;

use Analyzer;

class ForeachSourceNotVariable extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->atomIs("Foreach")
             ->outIs('SOURCE')
             ->atomIsNot(array('Variable', 'Staticproperty', 'Property', 'Array'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>