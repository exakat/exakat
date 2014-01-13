<?php 

class Cornac_Auditeur_Analyzer_Php_Envvar extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Environnement variable set';
	protected	$description = 'This is the special analyzer Php_Envvar (default doc).';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->code(array('putenv','getenv'))
                      ->firstChild()
                      ->nextSibling()
                      ->firstChild()
                      ->reportCode();
        $this->backend->run();

        return true;
	}
}

?>