<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extreflexion extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'reflexion.ini';
        
        parent::analyze();
    }
}

?>