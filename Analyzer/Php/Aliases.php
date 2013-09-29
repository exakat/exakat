<?php 

class Cornac_Auditeur_Analyzer_Php_Aliases extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Php aliases';
	protected	$description = 'Some PHP functions are in fact aliases. It is recommanded not to use them.';

    function dependsOn() {
        return array('Classes_MethodsCall');
    }

	public function analyse() {
        $this->cleanReport();

        $functions = Cornac_Auditeur_Analyzer::getPhpAliases();
        
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('functioncall')
                      ->attributes('Classes_MethodsCall','No')
                      ->code(array_keys($functions));
        $this->backend->run();
        
        return true;
	}
}

?>