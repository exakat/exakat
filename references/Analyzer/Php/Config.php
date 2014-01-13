<?php 

class Cornac_Auditeur_Analyzer_Php_Config extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Title for Php_Config';
	protected	$description = 'This is the special analyzer Php_Config (default doc).';

/*
// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array();
	}
*/
	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->code('ini_set')
                      ->firstChild()
                      ->nextSibling()
                      ->firstChild()
                      ->reportCode();
        $this->backend->run();

        $this->backend->type('functioncall')
                      ->code(array('set_include_path',
                                   'set_time_limit',
                                   'set_magic_quotes_runtime',
                                   'error_reporting'));
        $this->backend->run();

        return true;
	}
}

?>