<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmysql extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mysql.ini';
        
        parent::analyze();
    }
}

?>
