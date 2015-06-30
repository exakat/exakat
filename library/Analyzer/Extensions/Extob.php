<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extob extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ob.ini';
        
        parent::analyze();
    }
}

?>
