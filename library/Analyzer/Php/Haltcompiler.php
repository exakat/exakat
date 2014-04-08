<?php

namespace Analyzer\Php;

use Analyzer;

class Haltcompiler extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_NONE;
    protected $timeToFix = \Analyzer\Analyzer::S_NONE;

    public function analyze() {
        $this->atomIs("Halt");
    }
}

?>