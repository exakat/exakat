<?php 

class Cornac_Auditeur_Analyzer_Quality_CatchEmpty extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Empty catch';
	protected	$description = 'Spot catch block that are empty. This is a bad practice. ';

	public function analyse() {
        $this->cleanReport();

        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('_catch')
                      ->reportCode('cache_code')
                      ->getTaggedToken('block')
                      ->type('block')
                      ->width("= 1");
        $this->backend->run();

        return true;
	}
}

?>