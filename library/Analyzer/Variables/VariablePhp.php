<?php

namespace Analyzer\Variables;

use Analyzer;

class VariablePhp extends Analyzer\Analyzer {
    public static $variables = array('$_GET','$_POST','$_COOKIE','$_FILES','$_SESSION',
                                     '$_REQUEST','$_ENV', '$_SERVER',
                                     '$PHP_SELF','$HTTP_RAW_POST_DATA',
                                     '$HTTP_GET_VARS','$HTTP_POST_VARS', '$HTTP_ENV_VARS', '$HTTP_SERVER_VARS', '$HTTP_COOKIE_VARS',
                                     '$GLOBALS', 
                                     '$argv', '$argc');
    
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoIn('PROPERTY')
             ->code(VariablePhp::$variables, true);
    }
}

?>