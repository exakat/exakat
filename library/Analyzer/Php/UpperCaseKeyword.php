<?php

namespace Analyzer\Php;

use Analyzer;

class UpperCaseKeyword extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array("Class", "Foreach", 'As', 'Switch', 'For', 'Namespace', 'Use', 'Function',
                            'Try', 'Catch', 'Case', 'Default', 'Goto', 'Continue', 'Const', 'Break',
                            'Clone', 'DoWhile', 'While', 'Interface', 'Instanceof', 'Insteadof', 'Return',
                            'Throw', 'Trait', 'Interface', 'Var', 'Logical' ))
             ->isUpperCase('code');
        $this->prepareQuery();
        
        // implements, as in foreach(), end* are lost anyway 

    }
}

?>