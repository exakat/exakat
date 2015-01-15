<?php

namespace Report\Report;

class Test extends Premier {
    public function __construct($project, $client, $db) {
        parent::__construct($project, $client, $db);
    }

    public function prepare() {
        $this->createLevel1('Detailled');
        $analyzes = array('Analyzer\\Files\\DefinitionsOnly');
        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            $analyzes2[$analyzer->getName()] = $analyzer;
        }
        uksort($analyzes2, function($a, $b) { $a = strtolower($a); $b = strtolower($b); if ($a > $b) { return 1; } else { return $a == $b ? 0 : -1; } });

        if (count($analyzes) > 0) {
            $this->createLevel2('Results counts');
            $this->addContent('SimpleTableResultCounts', 'AnalyzerResultCounts');

            foreach($analyzes2 as $analyzer) {
                if ($analyzer->hasResults()) {
                    $this->createLevel2($analyzer->getName());
                    if (get_class($analyzer) == "Analyzer\\Php\\Incompilable") {
                        $this->addContent('Text', $analyzer->getDescription(), 'textlead');
                        $this->addContent('TableForVersions', $analyzer);
                    } elseif (get_class($analyzer) == "Analyzer\\Php\\ShortOpenTagRequired") {
                        $this->addContent('Text', $analyzer->getDescription(), 'textlead');
                        $this->addContent('SimpleTable', $analyzer, 'oneColumn');
                    } else {
                        $this->addContent('Text', $analyzer->getDescription(), 'textlead');
                        $this->addContent('Horizontal', $analyzer);
                    }
                }
            }
                
            // defined here, but for later use
            $definitions = new \Report\Content\Definitions($this->client);
            $definitions->setAnalyzers($analyzes);
        }
        
        return true;
    }
}

?>
