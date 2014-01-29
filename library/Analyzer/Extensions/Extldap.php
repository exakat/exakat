<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extldap extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ldap.ini';
        
        parent::analyze();
    }
}

?>