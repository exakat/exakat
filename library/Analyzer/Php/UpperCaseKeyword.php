<?php

namespace Analyzer\Php;

use Analyzer;

class UpperCaseKeyword extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array("Class", "Foreach", 'Switch', 'For', 'Namespace', 'Use', 'Function',
                            'Try', 'Catch', 'Case', 'Default', 'Goto', 'Continue', 'Const', 'Break',
                            'Clone', 'DoWhile', 'While', 'Interface', 'Instanceof', 'Insteadof', 'Return',
                            'Throw', 'Trait', 'Interface', 'Var', 'Logical' ))
             ->codeIsNot(array('&&', '||', '^', '&', '|'))
             ->isUpperCase('code');
        $this->prepareQuery();
        
        // some of the keywords are lost anyway : implements, extends, as in foreach(), endforeach/while/for/* are lost in tokenizer (may be keep track of that) 
        // As (in use commands) are not preserved. 
    }
}

?>