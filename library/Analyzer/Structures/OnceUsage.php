<?php

namespace Analyzer\Structures;

use Analyzer;

class OnceUsage extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MAJOR;
    protected $timeToFix = \Analyzer\Analyzer::T_SLOW;

    public function analyze() {
        $this->atomIs("Include")
             ->tokenIs(array('T_REQUIRE_ONCE', 'T_INCLUDE_ONCE'));
        $this->prepareQuery();
    }
}

?>