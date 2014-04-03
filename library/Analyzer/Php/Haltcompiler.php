<?php

namespace Analyzer\Php;

use Analyzer;

class VardumpUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = \Analyzer\Analyzer::S_NONE;
    protected $timeToFix = \Analyzer\Analyzer::S_NONE;

    public function analyze() {
        $this->functions = array('__halt_compiler');
        parent::analyze();
    }
}

?>