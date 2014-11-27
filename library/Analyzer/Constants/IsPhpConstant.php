<?php

namespace Analyzer\Constants;

use Analyzer;

class IsPhpConstant extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Constants\\ConstantUsage");
    }
    
    public function analyze() {
        $ini = $this->loadIni('php_constants.ini');
        $constants = $ini['constants'];
        
        $this->analyzerIs("Analyzer\\Constants\\ConstantUsage")
             ->code($constants);
        $this->prepareQuery();
    }
}

?>