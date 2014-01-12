<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmysqli extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'pcre.ini';
        
        parent::analyze();
    }
}

?>