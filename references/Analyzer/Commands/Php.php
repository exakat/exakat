<?php 

class Cornac_Auditeur_Analyzer_Commands_Php extends Cornac_Auditeur_Analyzer {
	protected	$title = 'PHP code in strings';
	protected	$description = 'Spot PHP code in strings.';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        foreach($sqls as $sql) {
            $this->backend->type('literals')
                          ->code('%<?php%');
            $this->backend->run('attributes');
        }

        return true;
	}
}

?>