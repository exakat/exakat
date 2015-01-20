<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extfpm extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'fpm.ini';
        
        parent::analyze();
    }
}

?>
