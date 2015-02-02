<?php

namespace Analyzer\Structures;

use Analyzer;

class ShellUsage extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // backtick shell calls
        $this->atomIs('Shell');
        $this->prepareQuery();

        // function calls with exec, etc
        $this->atomFunctionIs(array('exec', 'shell_exec', 'system', 'passthru', 'pcntl_exec', 'popen', 'pcntl_fork'));
        $this->prepareQuery();
    }
}

?>
