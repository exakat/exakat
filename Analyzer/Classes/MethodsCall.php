<?php 

class Cornac_Auditeur_Analyzer_Classes_MethodsCall extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Methods calls';
	protected	$description = 'This is the special analyzer Classes_MethodsCall (default doc).';

	public function analyse() {
        $this->cleanReport();

        $functions = Cornac_Auditeur_Analyzer::getPhpAliases();
        
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('method')
                      ->lastChild()
                      ->reportId();
        $this->backend->run('attributes');

        $this->backend->type('method_static')
                      ->lastChild()
                      ->reportId();
        $this->backend->run('attributes');

        return true;
	}
}
?>