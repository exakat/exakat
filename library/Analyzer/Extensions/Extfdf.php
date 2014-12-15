<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extfdf extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'fdf.ini';
        
        parent::analyze();
    }
}

?>
