<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmssql extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mssql.ini';

        parent::analyze();
    }
}

?>