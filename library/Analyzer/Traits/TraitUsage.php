<?php

namespace Analyzer\Traits;

use Analyzer;

class TraitUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Use')
             ->outIs('USE')
             ->traitDefinition();
        $this->prepareQuery();
    }
}

?>