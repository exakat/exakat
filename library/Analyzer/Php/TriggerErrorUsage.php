<?php

namespace Analyzer\Php;

use Analyzer;

class TriggerErrorUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = Analyzer\Analyzer::S_NONE;
    protected $timeToFix = Analyzer\Analyzer::T_NONE;
    
    public function analyze() {
        $this->functions = array('trigger_error', 
                                 'user_error');
        parent::analyze();
    }
}

?>