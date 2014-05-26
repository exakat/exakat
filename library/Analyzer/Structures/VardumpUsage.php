<?php

namespace Analyzer\Structures;

use Analyzer;

class VardumpUsage extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array('var_dump', 'print_r');
        parent::analyze();
    }
}

?>