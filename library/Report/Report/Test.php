<?php

namespace Report\Report;

class Test {
    private $client = null;
    private $db = null;

    private $summary = null;
    private $content = null;
    private $current = null;
    private $currentH1 = null;
    private $currentH2 = null;
    private $root    = null;

    public function __construct($client, $db) {
        $this->client  = $client;
        $this->db      = $db;

        $this->content = new \Report\Template\Section('');
        $this->current = $this->content;
        $this->root    = $this->content;
    }
    
    public function setProject($project) {
        $this->project = $project;
    }
    
    public function prepare() {
        $this->addContent('Text', 'Audit report for application');

        $this->createH1('Summary');
        $this->summary = $this->addContent('Summary', $this->root);

        $this->createH1('Report presentation');

        $this->createH2('Report synopsis'); 
        $this->addContent('Text', ' ');

        $this->createH2('Report synopsis2'); 
        $this->addContent('Text', '2');

        $this->createH2('Report synopsis3'); 
        $this->addContent('Text', '3');

        $this->createH2('Report configuration'); 

        $this->createH1('Compatibility');
        $this->summary = $this->addContent('Summary', $this->root);

        $h = $this->createH2('Compilations');
        $this->addContent('Text', 'This table is a summary of compilation situation. Every PHP script has been tested for compilation with the mentionned versions. Any error that was found is displayed, along with the kind of messsages and the list of erroneous files.');
        $d = new \Report\Content\Compilations();
        $d->setNeo4j($this->client);
        $c = $this->addContent('Compilations', $this->root);
        $d->collect();
        $c->setContent( $d );
        
        $h = $this->createH2('Compatibility53');
        $this->addContent('Text', 'This is a summary of the compatibility issues to move to PHP 5.3. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP 5.3. You must remove them before moving to this version.');

        $d = new \Report\Content\Compatibility53();
        $d->setNeo4j($this->client);
        $d->collect();
        $c = $this->addContent('Compatibility', $d);

        $h = $this->createH2('Compatibility54');
        $this->addContent('Text', 'This is a summary of the compatibility issues to move to PHP 5.4. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP 5.4. You must remove them before moving to this version.');

        $d = new \Report\Content\Compatibility54();
        $d->setNeo4j($this->client);
        $d->collect();
        $c = $this->addContent('Compatibility', $d);

        $h = $this->createH2('Compatibility55');
        $this->addContent('Text', 'This is a summary of the compatibility issues to move to PHP 5.5. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP 5.5. You must remove them before moving to this version.');

        $d = new \Report\Content\Compatibility55();
        $d->setNeo4j($this->client);
        $d->collect();
        $c = $this->addContent('Compatibility', $d);

        $h = $this->createH2('Compatibility56');
        $this->addContent('Text', 'This is a summary of the compatibility issues to move to PHP 5.6. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP 5.6. You must remove them before moving to this version.');

        $d = new \Report\Content\Compatibility56();
        $d->setNeo4j($this->client);
        $d->collect();
        $c = $this->addContent('Compatibility', $d);

//////////////////////////////////////////////////
/////// Detailled list of returns           //////
//////////////////////////////////////////////////
        $this->createH1('Detailled');
        $this->addContent('Text', 'intro');

        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP53'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56'));
        print_r($analyzes);

        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            $analyzes2[$analyzer->getName()] = $analyzer;
        }
        uksort($analyzes2, function($a, $b) { $a = strtolower($a); $b = strtolower($b); if ($a > $b) { return 1; } else { return $a == $b ? 0 : -1; } });

        if (count($analyzes) > 0) {
            foreach($analyzes2 as $analyzer) {
                if ($analyzer->hasResults()) {
                    $h = $this->createH2($analyzer->getName());
                    $h = $this->addContent('TextLead', $analyzer->getDescription());
                    $h = $this->addContent('Horizontal', $analyzer);
                }
            }
                
            // defined here, but for later use
            $defs = new \Report\Content\Definitions($client);
            $defs->setAnalyzers($analyzes);
        }
        
        return true;
    }
    
    public function render($format, $filename = null) {
        $class = "\\Report\\Format\\$format";
        $this->output = new $class();
        $this->output->setSummaryData($this->root);
        
        foreach($this->root->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            return $this->output->toFile($filename.'.'.$this->output->getExtension());
        } else {
            die("No filename?");
        }
    }
    
    private function createH1($name) {
        $section = $this->root->addContent('Section', $name);
        $section->setLevel(1);

        $this->current = $section;
        $this->currentH1 = $section;
    }

    function createH2($name) {
        // @todo check that current is level 1 ? 
        $section = $this->currentH1->addContent('Section', $name);
        $section->setLevel(2);

        $this->current = $section;
        $this->currentH2 = $section;
    }

    function createH3($name) {
        $this->current = $this->content->getCurrent()->getCurrent()->addSection($name, 3);
    }

    function addContent($type, $data = null) {
        return $this->current->addContent($type, $data);
    }
}

?>