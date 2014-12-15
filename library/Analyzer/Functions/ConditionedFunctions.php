<?php

namespace Analyzer\Functions;

use Analyzer;

class ConditionedFunctions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw('in.loop(1){true}{it.object.atom == "Ifthen"}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
