<?php

namespace Analyzer\Php;

use Analyzer;

class CaseForPSS extends Analyzer\Analyzer {
    protected $severity  = Analyzer\Analyzer::S_NONE;
    protected $timeToFix = Analyzer\Analyzer::T_NONE;

    protected $phpversion = '5.5-';

    public function analyze() {
        $this->atomIs("Identifier")
             ->code(array('parent', 'self', 'static'), false)
             ->codeIsNot(array('parent', 'self', 'static'), true);

        parent::analyze();
    }
}

?>