<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstantUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Identifier")
             ->tokenIs('T_STRING');
        $this->prepareQuery();

        $this->atomIs("Boolean");
        $this->prepareQuery();
    }
}

?>