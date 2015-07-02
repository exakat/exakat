<?php

namespace Analyzer\Performances;

use Analyzer;

class PrePostIncrement extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Postplusplus')
             ->hasNoIn(array('RIGHT', 'ARGUMENT'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
