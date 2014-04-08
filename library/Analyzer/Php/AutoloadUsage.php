<?php

namespace Analyzer\Php;

use Analyzer;

class AutoloadUsage extends Analyzer\Common\FunctionUsage {
    protected $severity  = Analyzer\Analyzer::S_NONE;
    protected $timeToFix = Analyzer\Analyzer::T_NONE;
    
    public function analyze() {
        $this->functions = array('spl_autoload_call', 
                                 'spl_autoload_functions',
                                 'spl_autoload_extensions',
                                 'spl_autoload_register',
                                 'spl_autoload_unregister',
                                 'spl_autoload',
                                 'spl_classes',
                                 'spl_object_hash',
                                 '__autoload',);
        parent::analyze();
    }
}

?>