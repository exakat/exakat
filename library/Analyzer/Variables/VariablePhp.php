<?php

namespace Analyzer\Variables;

use Analyzer;

class VariablePhp extends Analyzer\Analyzer {
    public static $variables = array('$_GET','$_POST','$_COOKIE','$_SERVER','$_FILES',
                                     '$_REQUEST','$_SESSION','$_ENV',
	                                 '$PHP_SELF','$HTTP_RAW_POST_DATA',
      	                             '$HTTP_GET_VARS','$HTTP_POST_VARS',
	                                 '$GLOBALS', '$argv',);
    
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoIn('PROPERTY')
             ->code(VariablePhp::$variables, true);
    }
}

?>