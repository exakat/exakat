<?php

namespace Analyzer\Structures;

use Analyzer;

class VardumpUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = \Analyzer\Analyzer::S_CRITICAL;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->functions = array('var_dump', 'print_r');
        parent::analyze();
    }
}

?>