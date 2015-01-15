<?php

namespace Analyzer\Structures;

use Analyzer;

class PhpinfoUsage extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array('phpinfo');

        parent::analyze();
    }
}

?>
