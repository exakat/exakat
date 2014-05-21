<?php

namespace Analyzer\Constants;

use Analyzer;

class InconsistantCase extends Analyzer\Analyzer {

    public function analyze() {
        
        $this->atomIs("Boolean")
             ->groupFilter("if (it.code == it.code.toLowerCase()) { x2 = 'lower'; } else if (it.code == it.code.toUpperCase()) { x2 = 'upper'; } else {x2 = 'mixed'; }", 10 / 100);
        $this->prepareQuery();
        
        $this->atomIs("Identifier")
             ->code('null', false)
             ->groupFilter("if (it.code == it.code.toLowerCase()) { x2 = 'lower'; } else if (it.code == it.code.toUpperCase()) { x2 = 'upper'; } else {x2 = 'mixed'; }", 10 / 100);
        $this->prepareQuery();
    }
}

?>