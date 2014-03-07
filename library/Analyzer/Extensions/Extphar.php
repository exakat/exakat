<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extphar extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'phar.ini';
        
        parent::analyze();
    }
}

?>