<?php

namespace Analyzer\Php;

use Analyzer;

class MiddleVersion extends Analyzer\Analyzer {
    public function analyze() {
        $data = new \Data\Methods();
        $bugfixes = $data->getBugFixes();
        
        $functions = array_unique(array_keys($bugfixes));
        $this->atomFunctionIs($this->makeFullNsPath($functions));
        $this->prepareQuery();
    }
}

?>
