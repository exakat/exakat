<?php

namespace Analyzer\Php;

use Analyzer;

class TriggerErrorUsage extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array('trigger_error', 
                                 'user_error');
        parent::analyze();
    }
}

?>