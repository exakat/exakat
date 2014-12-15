<?php

namespace Analyzer\Php;

use Analyzer;

class Php54RemovedFunctions extends Analyzer\Common\FunctionUsage {
    protected $phpVersion = "5.4-";
    
    public function analyze() {
        $this->functions = array('mcrypt_generic_end',
                                 'mysql_list_dbs');
        parent::analyze();
    }
}

?>
