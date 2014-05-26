<?php

namespace Analyzer\Structures;

use Analyzer;

class ExitUsage extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array('exit', 'die');
        parent::analyze();
    }
}

?>