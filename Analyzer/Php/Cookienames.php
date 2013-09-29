<?php 

class Cornac_Auditeur_Analyzer_Php_Cookienames extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Name of cookies';
	protected	$description = 'This is the special analyzer Php_Cookienames (default doc).';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_array')
                      ->firstChild()
                      ->code('$_COOKIE')
                      ->nextSibling()
                      ->reportCode();

        $this->backend->run();

        $this->backend->type('functioncall')
                      ->code('setcookie')
                      ->firstChild(3)
                      ->firstChild()
                      ->reportCode();

        $this->backend->run();

        $this->backend->type('functioncall')
                      ->code('header')
                      ->firstChild(3)
                      ->firstChild()
                      ->code('Set-Cookie%')
                      ->reportCode();

        $this->backend->run();

        return true;
	}
}

?>