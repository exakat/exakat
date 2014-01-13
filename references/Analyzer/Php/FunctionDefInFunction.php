<?php 

class Cornac_Auditeur_Analyzer_Php_FunctionDefInFunction extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Functions defined within functions';
	protected	$description = 'Spot functions defined within functions. Functions (like classes) should be defined in global';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_function')
                      ->notScope('global')
                      ->_class('');
        $this->backend->run();
        
        $this->backend->type('_function')
                      ->notScope('global')
                      ->notClass('');
        $this->backend->run();

        return true;
	}
}

?>
