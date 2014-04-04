<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsession extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'session.ini';
        
        parent::analyze();
    }
}

?>