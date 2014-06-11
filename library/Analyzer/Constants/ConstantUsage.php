<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstantUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Nsname")
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE'));
        $this->prepareQuery();

        $this->atomIs("Identifier")
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE'));
        $this->prepareQuery();

        $this->atomIs("Boolean");
        $this->prepareQuery();
    }
}

?>