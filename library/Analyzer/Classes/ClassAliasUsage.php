<?php

namespace Analyzer\Classes;

use Analyzer;

class ClassAliasUsage extends Analyzer\Common\FunctionUsage {
    /* Remove this if useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        $this->functions = array('class_alias');

        parent::analyze();
    }
}

?>
