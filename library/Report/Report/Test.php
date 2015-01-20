<?php

namespace Report\Report;

class Test extends Premier {
    public function __construct($project, $client, $db) {
        parent::__construct($project, $client, $db);
    }

    public function prepare() {
/////////////////////////////////////////////////////////////////////////////////////
/// Custom analyzers
/////////////////////////////////////////////////////////////////////////////////////
        
        $this->createLevel1('Custom');
        $this->createLevel2('Classes');
        $this->addContent('Text', <<<TEXT
This is a list of classes and their usage in the code. 

TEXT
);
        $content = $this->getContent('AnalyzerConfig');
        $content->setAnalyzer('Classes/AvoidUsing');
        $content->collect();
        
        $this->addContent('SimpleTable', $content, 'oneColumn'); 

        $analyzer = \Analyzer\Analyzer::getInstance('Analyzer\\Classes\\AvoidUsing', $this->client);
        $this->addContent('Horizontal', $analyzer);
        
        return true;
    }
}

?>
