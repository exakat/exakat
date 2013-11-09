<?php

namespace Analyzer\Extensions;

use Analyzer;

class Used extends Analyzer\Analyzer {

    function dependsOn() {
        return array('Analyzer\\Extensions\\Mcrypt', 
                     'Analyzer\\Extensions\\Kdm5');
    }

    function analyze() {
        
        $this->printQuery();
        $this->prepareQuery();
    }
}

?>