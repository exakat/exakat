<?php

namespace Analyzer\Php;

use Analyzer;

class AssertionUsage extends Analyzer\Common\FunctionDefinition {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code(array('assert', "assert_option"));
        $this->prepareQuery();
    }
}

?>
