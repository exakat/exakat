<?php

namespace Analyzer\Type;

use Analyzer;

class NoRealComparison extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Comparison')
             ->code(array('==', '!=', '===', '!=='))
             ->outIs(array('LEFT', 'RIGHT')) 
             ->atomIs('Real')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
