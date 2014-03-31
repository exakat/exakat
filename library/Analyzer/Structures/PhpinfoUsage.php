<?php

namespace Analyzer\Structures;

use Analyzer;

class PhpinfoUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = \Analyzer\Analyzer::S_CRITICAL;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->functions = 'phpinfo';
        parent::analyze();
    }
}

?>