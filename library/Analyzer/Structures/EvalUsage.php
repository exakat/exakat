<?php

namespace Analyzer\Structures;

use Analyzer;

class EvalUsage extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_CRITICAL;
    protected $timeToFix = \Analyzer\Analyzer::T_SLOW;

    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('eval', false);
        $this->prepareQuery();
    }
}

?>