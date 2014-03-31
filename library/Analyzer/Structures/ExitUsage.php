<?php

namespace Analyzer\Structures;

use Analyzer;

class ExitUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = Analyzer\Analyzer::S_MAJOR;
    protected $timeToFix = Analyzer\Analyzer::T_SLOW;
    
    public function analyze() {
        $this->functions = array('exit', 'die');
        parent::analyze();
    }
}

?>