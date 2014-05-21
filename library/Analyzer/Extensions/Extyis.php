<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extyis extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'yis.ini';
        
        parent::analyze();
    }
}

?>