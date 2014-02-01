<?php

namespace Analyzer\Functions;

use Analyzer;

class Closures extends Analyzer\Analyzer {
    
    protected $themes = array('Inventory', 'Appinfo');
    
    function analyze() {
        $this->atomIs("Function")
             ->is('lambda', 'true');
    }
}

?>