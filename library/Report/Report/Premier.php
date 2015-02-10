<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Report\Report;

use Report\Report;

class Premier extends Report {
    private $projectUrl    = null;

    public function __construct($project, $client, $db) {
        parent::__construct($project, $client, $db);
    }
    
    public function setProject($project) {
        $this->project = $project;
    }

    public function setProjectUrl($projectUrl) {
        $this->projectUrl = $projectUrl;
    }
    
    public function prepare() {
        $this->createLevel1('Report presentation');

/////////////////////////////////////////////////////////////////////////////////////
/// Audit introduction
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel2('Audit configuration'); 
        $this->addContent('Text', 'Presentation of the audit', 'first');
        $this->addContent('SimpleTable', 'ReportInfo', 'reportinfo'); 

        $this->createLevel2('Application configuration'); 
        $this->addContent('Text', 'Presentation of the application');

/////////////////////////////////////////////////////////////////////////////////////
/// Main dashboards
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Analysis');
        $this->createLevel2('Code smells');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Analyze');
        $analyzer->collect();
        $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');

        $this->createLevel2('Coding Conventions');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Coding Conventions');
        $analyzer->collect();
        $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');

        $this->createLevel2('Dead code');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Dead code');
        $analyzer->collect();
        $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');

        $this->createLevel2('Security');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Security');
        $analyzer->collect();
        $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');

/////////////////////////////////////////////////////////////////////////////////////
/// Compilations
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Compilation');
        $this->addContent('Text', 'This table is a summary of compilation situation. Every PHP script has been tested for compilation with the mentionned versions. Any error that was found is displayed, along with the kind of messsages and the list of erroneous files.');
        $this->createLevel2('Compile');
        $this->addContent('Compilations', 'Compilations');

        //'5.2' => '52', , '7.0' => '70'
        $versions = array('5.3' => '53', '5.4' => '54', '5.5' => '55', '5.6' => '56');
        foreach($versions as $version => $code) {
            $this->createLevel2('Compatibility '.$version);
            $this->addContent('Text', 'This is a summary of the compatibility issues to move to PHP '.$version.'. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP '.$version.'. You must remove them before moving to this version.');
            $this->addContent('Compatibility', 'Compatibility'.$code);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Detailled
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Detailled');
        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            $analyzes2[$analyzer->getDescription()->getName()] = $analyzer;
        }
        uksort($analyzes2, function($a, $b) { 
            $a = strtolower($a); 
            $b = strtolower($b); 
            if ($a > $b) { 
                return 1; 
            } else { 
                return $a == $b ? 0 : -1; 
            } 
        });

        if (count($analyzes) > 0) {
            $this->createLevel2('Results counts');
            $this->addContent('SimpleTableResultCounts', 'AnalyzerResultCounts');

            foreach($analyzes2 as $analyzer) {
                if ($analyzer->hasResults()) {
                    $this->createLevel2($analyzer->getDescription()->getName());
                    if (get_class($analyzer) == "Analyzer\\Php\\Incompilable") {
                        $this->addContent('Text', $analyzer->getDescription()->getDescription(), 'textlead');
                        $this->addContent('TableForVersions', $analyzer);
                    } elseif (get_class($analyzer) == "Analyzer\\Php\\ShortOpenTagRequired") {
                        $this->addContent('Text', $analyzer->getDescription()->getDescription(), 'textlead');
                        $this->addContent('SimpleTable', $analyzer, 'oneColumn');
                    } else {
                        $description = $analyzer->getDescription()->getDescription();
                        if ($description == '') {
                            $description = 'No documentation yet';
                        }
                        if ($clearPHP = $analyzer->getDescription()->getClearPHP()) {
                            $this->addContent('Text', 'clearPHP : '.$clearPHP.'<br />', 'textlead');
                            print $analyzer->getDescription()->getName()."\n";
                        }
                        $this->addContent('Text', $description, 'textlead');
                        $this->addContent('Horizontal', $analyzer);
                    }
                }
            }
                
            // defined here, but for later use
            $definitions = new \Report\Content\Definitions($this->client);
            $definitions->setAnalyzers($analyzes);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Application
/////////////////////////////////////////////////////////////////////////////////////
        
        $this->createLevel1('Application');
        $this->createLevel2('Appinfo()');
        $this->addContent('Text', <<<TEXT
This is an overview of your application.

Ticked <i class="icon-ok"></i> information are features used in your application. Non-ticked are feature that are not in use in the application.

TEXT
);
        $this->addContent('Tree', 'Appinfo');

        $this->createLevel2('Directive');
        $this->addContent('Text', <<<TEXT
This is an overview of the recommended directives for your application. 
TEXT
);
        $this->addContent('Directives', 'Directives');

        $this->createLevel1('Stats');
        $this->addContent('Text', <<<TEXT
These are various stats of different structures in your application.

TEXT
);
        $this->addContent('SectionedHashTable', 'AppCounts');

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

/////////////////////////////////////////////////////////////////////////////////////
/// Annexes
/////////////////////////////////////////////////////////////////////////////////////
        $this->createLevel1('Annexes');

        $this->createLevel2('Documentation');
        $this->addContent('Definitions', $definitions, 'annexes');

        $this->createLevel2('Processed files');
        $this->addContent('Text', 'This is the list of processed files. Any file that is in the project, but not in the list below was omitted in the analyze. 
        
This may be due to configuration file, compilation error, wrong extension (including no extension). ', 'textLead');

        $this->addContent('SimpleTable', 'ProcessedFileList', 'oneColumn');

        // List of dynamic calls
        $analyzer = \Analyzer\Analyzer::getInstance('Structures/DynamicCalls', $this->client);
        if ($analyzer->hasResults()) {
            $this->createLevel2('Dynamic code');
            $this->addContent('Text', 'This is the list of dynamic call. They are not checked by the static analyzer, and the analysis may be completed with a manual check of that list.', 'textLead');
            $this->addContent('Horizontal', $analyzer);
        }
    }
}

?>
