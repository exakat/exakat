<?php

namespace Analyzer\Themes;

class Web extends \Analyzer\Analyzer {

	public function dependsOn() {
        $dependencies = array(
            'EvalUsage',
            'VardumpUsage',
            'PhpinfoUsage',
            'CalltimePassByReference',
            'ErrorReportingWithInteger',
            'PlusEgalOne',
            'ShortTags',
            'StrposCompare',
            'Break0',
            'BreakNonInteger',
            'NotNot',
            'Noscream',
            'ForeachSourceNotVariable',
            'NonPPP',
            
        );
        return $dependencies;
	}
}

?>