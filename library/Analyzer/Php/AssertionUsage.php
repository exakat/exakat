<?php

namespace Analyzer\Php;

use Analyzer;

class AssertionUsage extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array('assert', 'assert_option');
        
        parent::analyze();
    }
}

?>
