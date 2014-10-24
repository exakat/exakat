<?php

namespace Analyzer\Structures;

use Analyzer;

class ConstantComparisonConsistance extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Comparison")
             ->groupFilter("if (it.out('LEFT').next().atom in ['Integer', 'Float', 'String', 'Boolean', 'Null']) { x2 = 'left'; } else { x2 = 'right'; }", 10 / 100);
        $this->prepareQuery();
    }
}

?>