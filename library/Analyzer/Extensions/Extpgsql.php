<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extpgsql extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'pgsql.ini';
        
        parent::analyze();
    }
}

?>
