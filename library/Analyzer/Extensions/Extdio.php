<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extdio extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'dio.ini';
        
        parent::analyze();
    }
}

?>
