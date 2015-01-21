<?php

namespace Analyzer\Structures;

use Analyzer;

class EvalUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->code(array('eval', 'create_function'), false);
        $this->prepareQuery();
    }
}

?>
