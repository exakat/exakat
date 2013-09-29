<?php 

class Cornac_Auditeur_Analyzer_Commands_Xml extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Xml strings';
	protected	$description = 'Spot strings containing XML.';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        foreach($sqls as $sql) {
            $this->backend->type('literals')
                          ->code('%<?xml%');
            $this->backend->run('attributes');
        }

        return true;
	}
}

?>