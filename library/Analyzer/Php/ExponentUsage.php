<?php

namespace Analyzer\Php;

use Analyzer;

class ExponentUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Power");
        $this->prepareQuery();

        $this->atomIs("Assignation")
             ->tokenIs('T_POW_EQUAL');
        $this->prepareQuery();
    }
}

?>
