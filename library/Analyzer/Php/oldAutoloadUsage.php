<?php

namespace Analyzer\Php;

use Analyzer;

class oldAutoloadUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->hasNoClass()
             ->outIs('NAME')
             ->code('__autoload')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
