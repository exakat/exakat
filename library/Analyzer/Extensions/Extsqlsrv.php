<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsqlsrv extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'sqlsrv.ini';
        
        parent::analyze();
    }
}

?>
