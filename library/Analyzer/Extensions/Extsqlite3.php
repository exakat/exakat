<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsqlite3 extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'sqlite3.ini';
        
        parent::analyze();
    }
}

?>
