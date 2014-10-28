<?php

namespace Analyzer\Php;

use Analyzer;

class SetHandlers extends Analyzer\Common\FunctionDefinition {
    public function analyze() {
        $this->functions = array(
'set_error_handler',
'set_exception_handler',
'session_set_save_handler',
'register_tick_function',
'register_shutdown_function',
);
        parent::analyze();
    }
}

?>