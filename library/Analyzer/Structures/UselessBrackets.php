<?php

namespace Analyzer\Structures;

use Analyzer;

class UselessBrackets extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Sequence')
             ->is('block', true)
             ->hasNoIn('BLOCK') ;
        $this->prepareQuery();
    }
}

?>
