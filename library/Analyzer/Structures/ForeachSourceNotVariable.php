<?php

namespace Analyzer\Structures;

use Analyzer;

class ForeachSourceNotVariable extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Foreach")
             ->out('SOURCE')
             ->atomIsNot(array('Variable', 'Staticproperty', 'Property', 'Array'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>