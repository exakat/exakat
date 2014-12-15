<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extbcmath extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'bcmath.ini';
        
        parent::analyze();
    }
}

?>
