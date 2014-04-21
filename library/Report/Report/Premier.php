<?php

namespace Report\Report;

class Premier {
    private $client = null;
    private $db = null;

    private $summary = null;
    private $content = null;
    private $current = null;
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

        $this->createH2('Report configuration'); 

        $ReportInfo = new \Report\Content\ReportInfo($this->project);
        $ReportInfo->setNeo4j($this->client);
        $ReportInfo->setMySQL($this->db);
        $ReportInfo->setProject($this->project);
        $ReportInfo->collect();

        $ht = $this->addContent('HashTable', $ReportInfo); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)
        $ht->setAnalyzer('ReportInfo');
        
        $this->createH1('Dashboard');
        $groupby = new \Report\Content\GroupBy($this->client);
        $groupby->setGroupby('getSeverity');
        $groupby->setCount('toCount');
        $groupby->setSort(array('Critical', 'Major', 'Minor'));
        
        $groupby->addAnalyzer(array(  'Structures\\StrposCompare', 
                                      'Structures\\Iffectation',
                                      'Structures\\ErrorReportingWithInteger',
                                      'Structures\\ForWithFunctioncall',
                                      'Structures\\ForeachSourceNotVariable',
                                      'Variables\\VariableUsedOnce',
                                      'Variables\\VariableNonascii',
                                      'Structures\\EvalUsage',
                                      'Structures\\OnceUsage',
                                      'Structures\\VardumpUsage',
                                      'Structures\\PhpinfoUsage',
                                      'Classes\\NonPpp',
                                      'Php/Incompilable',
                                      'Constants/ConstantStrangeNames',

                                      'Structures\\NotNot',
                                      'Structures\\Noscream',
                                      'Structures\\toStringThrowsException',
                                      'Structures\\CalltimePassByReference',
                                      'Structures\\Break0',
                                      'Structures\\BreakNonInteger',
                                ));
        $groupby->setName('Severity repartition');

        $ht = $this->addContent('Camembert', $groupby); // presentation of the report, its organization and extra information on its configuration (such as PHP version used, when, version of software, human reviewer...)

        ///// Application analyzes 
        $analyzes = array('Structures\\StrposCompare', 
                          'Structures\\Iffectation',
                          'Structures\\ErrorReportingWithInteger',
                          'Structures\\ForWithFunctioncall',
                          'Structures\\ForeachSourceNotVariable',
                          'Variables\\VariableUsedOnce',
                          'Variables\\VariableNonascii',
                          'Structures\\EvalUsage',
                          'Structures\\OnceUsage',
                          'Structures\\VardumpUsage',
                          'Structures\\PhpinfoUsage',
                          'Classes\\NonPpp',
                          'Php/Incompilable',
                          'Constants/ConstantStrangeNames',

                          'Structures\\NotNot',
                          'Structures\\Noscream',
                          'Structures\\toStringThrowsException',
                          'Structures\\CalltimePassByReference',
                          'Structures\\Break0',
                          'Structures\\BreakNonInteger',
                          );
        // hash with config
        /*
        foreach($analyzes as $id => $a) {
            if (!in_array(str_replace('\\', '/', $a), $config['analyzer'])) {
                unset($analyzes[$id]);
            }
        }
        */

        if (count($analyzes) > 0) {
            $h1 = false;

            $analyzer = new \Report\Content\AnalyzerResultCounts();
            $analyzer->setNeo4j($this->client);
            $analyzer->setAnalyzers($analyzes);
            $h = $this->createH2($analyzer->getName());
            $h = $this->addContent('Table', $analyzer);

            foreach($analyzes as $a) {
                $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            
//            $analyzer = \Analyzer\Analyzer::getInstance('Common/Bunch', $this->client);
//            $analyzer->setBunch($analyzes);
            
                /*
                if (!$analyzer->checkPhpVersion('5.3.26')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }

                if (!$analyzer->checkPhpConfiguration('aspTags')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }
                */
                
                /*
                
                if ($analyzer->hasResults()) {
                    $this->no_output[] = $analyzer->getName();
                    continue;
                }
                */
                
                if ($analyzer->hasResults()) {
                    $h = $this->createH2($analyzer->getName());
                    $h = $this->addContent('Horizontal', $analyzer);
                }
            }
                
            // defined here, but for later use
            $defs = new \Report\Content\Definitions($client);
            $defs->setAnalyzers($analyzes);
        }

        $this->createH1('Application information');

        $ht = $this->addContent('Text', 'This is an overview of your application');
        $analyze = new \Report\Content\Appinfo();
        $analyze->setNeo4j($this->client);
        $analyze->collect();
//        $ht = $this->addContent('SectionedHashTable', $analyze);


        $this->createH1('Inventories');
        ///// Application analyzes 
        $analyzes = array('Php/Incompilable',
                          'Variables/Variablenames',
                          'Classes/Classnames',
                          'Type/Integer',
                          'Type/HttpHeader',
                          'Type/UnicodeBlock',
                          'Type/Md5String',
                          'Type/Email',
                          );
        // hash with config
        /*
        foreach($analyzes as $id => $a) {
            if (!in_array(str_replace('\\', '/', $a), $config['analyzer'])) {
                unset($analyzes[$id]);
            }
        }
        */

        if (count($analyzes) > 0) {
            $h1 = false;
            
            foreach($analyzes as $name) {
                $analyse = \Analyzer\Analyzer::getInstance($name, $this->client);
                
                /*
                if (!$analyzer->checkPhpVersion('5.3.26')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }

                if (!$analyzer->checkPhpConfiguration('aspTags')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }
                */
                
                /*
                
                if ($analyzer->hasResults()) {
                    $this->no_output[] = $analyzer->getName();
                    continue;
                }
                */

                if ($analyse->hasResults()) {
                    $this->createH2($analyse->getName());
                    $this->addContent('Text', $analyse->getDescription());

                    if (in_array($name, array('Php/Incompilable'))) {
                        $this->addContent('Liste', $analyse);
                    } else {
                        $ht = $this->addContent('HashTable', $analyse);
                        $ht->setCountedValues();
                    }

                } 
            }
        }

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
    }

    function createH2($name) {
        // @todo check that current is level 1 ? 
        $section = $this->current->addContent('Section', $name);
        $section->setLevel(2);

        $this->current = $section;
    }

    function createH3($name) {
        $this->current = $this->content->getCurrent()->getCurrent()->addSection($name, 3);
    }

    function addContent($type, $data = null) {
        return $this->current->addContent($type, $data);
    }
}

?>