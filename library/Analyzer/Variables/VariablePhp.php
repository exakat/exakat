<?php

namespace Analyzer\Variables;

use Analyzer;

class VariablePhp extends Analyzer\Analyzer {
    public static $variables = array('$_GET','$_POST','$_COOKIE','$_FILES','$_SESSION',
                                     '$_REQUEST','$_ENV', '$_SERVER',
                                     '$PHP_SELF','$HTTP_RAW_POST_DATA',
                                     '$HTTP_GET_VARS','$HTTP_POST_VARS', '$HTTP_POST_FILES', '$HTTP_ENV_VARS', '$HTTP_SERVER_VARS', '$HTTP_COOKIE_VARS',
                                     '$GLOBALS', '$this',
                                     '$argv', '$argc');

    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->code(VariablePhp::$variables, true);
    }
}

?>
