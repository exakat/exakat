<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyDefinition extends Analyzer\Analyzer {

    function analyze() {

        // ppp $var;
        $this->atomIs("Ppp")
             ->out('DEFINE');
             
        // @todo probably needs to run several analyze, depending on different situations. 
    }
}

?>