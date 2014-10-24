<?php

namespace Report\Report;

class Premier {
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

        $this->createH1('Report presentation');

        $this->createH2('Report synopsis'); 
        $this->addContent('Text', <<<TEXT
DISCLAIMER : This is an alpha version of the software. We are working hard to make it better, so your feedback is always appreciated : damien.seguy@gmail.com.
        
The PHP code is firstly tokenized by PHP itself and an AST tree is build. Then, we run on this structured representation of the code our analyzers, that will look for various situations. 

Situations may be code poorly written, code that compile and run but won\'t do what is expected, security holes, performances errors. You will find them in the "Analysis" section, along with the spotted code, and its file coordinates.

Along the way, we gather many informations about the application itself, which are gathered in the "Application information" tab. There, you\'ll have an overview of PHP features that are used in the code : extensions, features such as ticks, shell commands, typehint, recursive methods or variables variables. This is a good way to track all the technology invested in your code. 

Finaly, some definitions are gathered in the "Annex".

TEXT
);

        $this->createH2('Report configuration'); 

        $ReportInfo = new \Report\Content\ReportInfo($this->project);
        $ReportInfo->setProject($this->project);
        $ReportInfo->setNeo4j($this->client);
        $ReportInfo->setMySQL($this->db);
        $ReportInfo->collect();

        $ht = $this->addContent('SimpleTable', $ReportInfo); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)
        $ht->setAnalyzer('ReportInfo');
        
        $this->createH1('Analysis');
        $this->addContent('Text', 'intro');

////////////////////////////////
// Dashboard                  //
////////////////////////////////
        $this->createH2('Errors');
        $groupby = new \Report\Content\GroupBy($this->client);
        $groupby->setGroupby('getSeverity');
        $groupby->setCount('toCount');
        $groupby->setSort(array('Critical', 'Major', 'Minor'));

        $row = $this->addContent('Row', null);
        
        $groupby->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Analyze') );
        $groupby->setName('Severity repartition');
        
        $row->addLeftContent('Camembert', $groupby); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $infobox = new \Report\Content\Infobox();
        $infobox->setNeo4j($this->client);
        $infobox->setMySQL($this->db);
        $infobox->setSeverities($groupby->toArray());
        $infobox->collect();
        $ht = $row->addRightContent('Infobox', $infobox); 

        $row2 = $this->addContent('Row', null);
        $listBySeverity = new \Report\Content\ListBySeverity($this->client);
        $listBySeverity->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'));
        $listBySeverity->setName('Top 5 errors');
        $ht = $row2->addLeftContent('Top5', $listBySeverity); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $listByFile = new \Report\Content\listByFile($this->client);
        $listByFile->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'));
        $listByFile->setName('Top 5 files');
        $ht = $row2->addRightContent('Top5', $listByFile);

/////////////////////////////////
// Dashboard Coding Convention //
/////////////////////////////////
        $this->createH2('Coding Conventions');
        $groupby = new \Report\Content\GroupBy($this->client);
        $groupby->setGroupby('getSeverity');
        $groupby->setCount('toCount');
        $groupby->setSort(array('Critical', 'Major', 'Minor'));

        $row = $this->addContent('Row', null);
        
        $groupby->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions') );
        $groupby->setName('Severity repartition');
        
        $row->addLeftContent('Camembert', $groupby); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $infobox = new \Report\Content\Infobox();
        $infobox->setNeo4j($this->client);
        $infobox->setMySQL($this->db);
        $infobox->setSeverities($groupby->toArray());
        $infobox->collect();
        $ht = $row->addRightContent('Infobox', $infobox); 

        $row2 = $this->addContent('Row', null);
        $listBySeverity = new \Report\Content\ListBySeverity($this->client);
        $listBySeverity->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
        $listBySeverity->setName('Top 5 errors');
        $ht = $row2->addLeftContent('Top5', $listBySeverity); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $listByFile = new \Report\Content\listByFile($this->client);
        $listByFile->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
        $listByFile->setName('Top 5 files');
        $ht = $row2->addRightContent('Top5', $listByFile);

/////////////////////////////////
// Dashboard Dead code         //
/////////////////////////////////
        $this->createH2('Dead code');
        $groupby = new \Report\Content\GroupBy($this->client);
        $groupby->setGroupby('getSeverity');
        $groupby->setCount('toCount');
        $groupby->setSort(array('Critical', 'Major', 'Minor'));

        $row = $this->addContent('Row', null);
        
        $groupby->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Dead code') );
        $groupby->setName('Severity repartition');
        
        $row->addLeftContent('Camembert', $groupby); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $infobox = new \Report\Content\Infobox();
        $infobox->setNeo4j($this->client);
        $infobox->setMySQL($this->db);
        $infobox->setSeverities($groupby->toArray());
        $infobox->collect();
        $ht = $row->addRightContent('Infobox', $infobox); 

        $row2 = $this->addContent('Row', null);
        $listBySeverity = new \Report\Content\ListBySeverity($this->client);
        $listBySeverity->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Dead code'));
        $listBySeverity->setName('Top 5 errors');
        $ht = $row2->addLeftContent('Top5', $listBySeverity); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        $listByFile = new \Report\Content\listByFile($this->client);
        $listByFile->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers('Dead code'));
        $listByFile->setName('Top 5 files');
        $ht = $row2->addRightContent('Top5', $listByFile);

////////////////////////////////
// Compilations               //
////////////////////////////////

        $h = $this->createH1('Compilations');
        $h = $this->createH2('Compile');
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
        
////////////////////////////////
// Application analyzes       //
////////////////////////////////
        $this->createH1('Detailled');
        $this->addContent('Text', 'intro');

        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            $analyzes2[$analyzer->getName()] = $analyzer;
        }
        uksort($analyzes2, function($a, $b) { $a = strtolower($a); $b = strtolower($b); if ($a > $b) { return 1; } else { return $a == $b ? 0 : -1; } });

        if (count($analyzes) > 0) {
            $h1 = false;

            $analyzer = new \Report\Content\AnalyzerResultCounts();
            $analyzer->setNeo4j($this->client);
            $analyzer->setAnalyzers($analyzes);
            $h = $this->createH2($analyzer->getName());
            $h = $this->addContent('SimpleTableResultCounts', $analyzer);

            foreach($analyzes2 as $analyzer) {
                if ($analyzer->hasResults()) {
                    $h = $this->createH2($analyzer->getName());
                    if ($a == "Php/Incompilable") {
                        $h = $this->addContent('TableForVersions', $analyzer);
                    } else {
                        $h = $this->addContent('TextLead', $analyzer->getDescription());
                        $h = $this->addContent('Horizontal', $analyzer);
                    }
                }
            }
                
            // defined here, but for later use
            $defs = new \Report\Content\Definitions($client);
            $defs->setAnalyzers($analyzes);
        }

        $this->createH1('Application information');

        $ht = $this->addContent('Text', <<<TEXT
This is an overview of your application.

Ticked <i class="icon-ok"></i> information are features used in your application. Non-ticked are feature that are not in use in the application.

TEXT
);
        $analyze = new \Report\Content\Appinfo();
        $analyze->setNeo4j($this->client);
        $analyze->collect();
        $ht = $this->addContent('Tree', $analyze);

        $this->createH1('Stats');

        $ht = $this->addContent('Text', <<<TEXT
These are various stats of different structures in your application.

TEXT
);
        $analyze = new \Report\Content\AppCounts();
        $analyze->setNeo4j($this->client);
        $analyze->collect();
        $ht = $this->addContent('SectionedHashTable', $analyze);

        $this->createH1('Annexes');
        $this->createH2('Documentation');
        $this->addContent('Definitions', $defs);
        
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