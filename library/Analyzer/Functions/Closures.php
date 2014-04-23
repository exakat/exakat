<?php

namespace Analyzer\Functions;

use Analyzer;

class Closures extends Analyzer\Analyzer {
    
    protected $themes = array('Inventory', 'Appinfo');
    
    public function analyze() {
        $this->atomIs("Function")
             ->is('lambda');
    }
}

?>