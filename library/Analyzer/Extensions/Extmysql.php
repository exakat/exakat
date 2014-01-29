<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extgd extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mysql.ini';
        
        parent::analyze();
    }
}

?>