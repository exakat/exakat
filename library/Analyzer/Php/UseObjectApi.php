<?php

namespace Analyzer\Php;

use Analyzer;

class UseObjectApi extends Analyzer\Analyzer {
    public function analyze() {
        $functions = $this->loadIni('function_to_oop.ini', 'function');
        
        $this->atomFunctionIs(array_keys($functions));
        $this->prepareQuery();
    }
}

?>
