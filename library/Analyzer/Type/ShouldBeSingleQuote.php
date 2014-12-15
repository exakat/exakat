<?php

namespace Analyzer\Type;

use Analyzer;

class ShouldBeSingleQuote extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('String')
             ->is('delimiter', '"')
             ->hasNoOut('CONTAIN');
        $this->prepareQuery();
    }
}

?>