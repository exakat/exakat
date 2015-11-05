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

    private $dashboards = array('Analyze'               => 'Code smells', 
                                'Security'              => 'Security', 
                                'Performances'          => 'Performances', 
                                'Dead code'             => 'Dead code',
                                'CompatibilityPHP53'    => 'Compatibility 53',
                                'CompatibilityPHP54'    => 'Compatibility 54',
                                'CompatibilityPHP55'    => 'Compatibility 55',
                                'CompatibilityPHP56'    => 'Compatibility 56',
                                'CompatibilityPHP70'    => 'Compatibility 70',
                                'CompatibilityPHP71'    => 'Compatibility 71',
                                );

    public function __construct($project) {
        parent::__construct($project);
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

/////////////////////////////////////////////////////////////////////////////////////
/// Main dashboards
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Analysis');

        $this->createLevel2('Code smells');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Analyze');
        $analyzer->collect();
        if ($analyzer->hasResults()) {
            $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');
        }  else {
            $this->addContent('Text', 'Nothing noteworthy was found. We looked hard, but it looks clean. Good job!');
        }

        $this->createLevel2('Dead code');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Dead code');
        $analyzer->collect();
        if ($analyzer->hasResults()) {
            $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');
        }  else {
            $this->addContent('Text', 'Nothing noteworthy was found. We looked hard, but it looks clean. Good job!');
        }

        $this->createLevel2('Security');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Security');
        $analyzer->collect();
        if ($analyzer->hasResults()) {
            $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');
        } else {
            $this->addContent('Text', 'Nothing noteworthy was found. We looked hard, but it looks clean. Good job!');
        }

        $this->createLevel2('Performances');
        $analyzer = $this->getContent('Dashboard');
        $analyzer->setThema('Performances');
        $analyzer->collect();
        if ($analyzer->hasResults()) {
            $this->addContent('Dashboard', $analyzer, 'deadCodeDashboard');
        } else {
            $this->addContent('Text', 'Nothing noteworthy was found. We looked hard, but it looks clean. Good job!');
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Compatibility
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Compatibility');
        $this->addContent('Text', 'This table is a summary of compilation situation. Every PHP script has been tested for compilation with the mentionned versions. Any error that was found is displayed, along with the kind of messsages and the list of erroneous files.');
        $this->createLevel2('Compile');

        $config = \Config::factory();
        $compilations = new \Report\Content\Compilations();
        $compilations->setVersions($config->other_php_versions);
        $compilations->collect();
        $this->addContent('Compilations', $compilations);

        $config = \Config::factory();
        foreach($config->other_php_versions as $code) {
            // No Compatibility with PHP 5.2 is done. Just ignored.
            if ($code == 52) { continue; }

            $version = substr($code, 0, 1).'.'.substr($code, 1);
            $this->createLevel2('Compatibility '.$version);
            $this->addContent('Text', 
            'This is a summary of the compatibility of the code with PHP '.$version.'. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP '.$version.'. You must remove them before moving to this version.
            
<i class="fa fa-check-square-o green"></i> : OK. Found nothing. <i class="fa fa-exclamation red"></i> : Found something worth checking. <i class="fa fa-stethoscope"></i> : Analyze is not compatible with used php version or with its compilation');
            $this->addContent('Compatibility', 'Compatibility'.$code);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Detailled by analyzer
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('By analyze');
        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Dead Code'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Security'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP53'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP70'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP71')
                                );
        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a);
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
            $resultsCount = new \Report\Content\AnalyzerResultCounts();
            $resultsCount->setAnalyzers($analyzes2);
            $resultsCount->collect();
            $this->addContent('SimpleTableResultCounts', $resultsCount, 'SimpleTableResultCounts');

            foreach($analyzes2 as $analyzer) {
                if ($analyzer->hasResults()) {
                    $this->createLevel2($analyzer->getDescription()->getName());
                    if (get_class($analyzer) == "Analyzer\\Php\\Incompilable") {
                        $this->addContent('TextLead', $analyzer->getDescription()->getDescription(), 'textLead');
                        $this->addContent('TableForVersions', $analyzer);
                    } elseif (get_class($analyzer) == "Analyzer\\Php\\ShortOpenTagRequired") {
                        $this->addContent('TextLead', $analyzer->getDescription()->getDescription(), 'textLead');
                        $this->addContent('SimpleTable', $analyzer, 'oneColumn');
                    } else {
                        $description = $analyzer->getDescription()->getDescription();
                        if ($description == '') {
                            $description = 'No documentation yet';
                        }
                        if ($clearPHP = $analyzer->getDescription()->getClearPHP()) {
                            $this->addContent('Text', 'clearPHP : <a href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$clearPHP.'.md">'.$clearPHP.'</a><br />', 'textLead');
                        }


                        $this->addContent('TextLead', $description, 'textLead');
                        $themelist = new \Report\Content\ThemeList($list, $this->dashboards);
                        $list = $analyzer->getThemes();
                        $themelist->setList($list);
                        $themelist->setDashboards($this->dashboards);
                        $themelist->collect();
                        
                        $this->addContent('ThemeList', $themelist);

                        $this->addContent('Horizontal', $analyzer);
                    }
                }
            }
            
            
            // defined here, but for later use
            $definitions = new \Report\Content\Definitions(null);
            $definitions->setAnalyzers($analyzes);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Detailled by file
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('By file');
        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Dead Code'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Security'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP53'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP70'),
                                \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP71')
                                );

        $fileList = [];
        $analyzesList = [];
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a);
            $analyzerName = $analyzer->getDescription()->getName();

            $filesForAnalyze = $analyzer->getFileList();
            if (empty($filesForAnalyze[0])) { continue; }
            foreach($filesForAnalyze[0] as $file => $count) {
                if (isset($fileList[$file])) {
                    $fileList[$file][] = $analyzerName;
                } else {
                    $fileList[$file] = array($analyzerName);
                }

                $rows = $analyzer->getArray();
                foreach($rows as $row) {
                    if ($row['file'] != $file) { continue; }
                    if (isset($analyzesList[$file])) {
                        $analyzesList[$file][] = ['code' => $row['code'], 
                                                  'line' => $row['line'],
                                                  'file' => $analyzerName];
                    } else {
                        $analyzesList[$file] = [['code' => $row['code'], 
                                                 'line' => $row['line'],
                                                 'file' => $analyzerName]];
                    }
                }
            }
        }
        
        foreach($fileList as &$analyzes) {
            $analyzes = array_count_values($analyzes);
            ksort($analyzes);
        }
        (unset) $analyzes;
        
        uksort($fileList, function($a, $b) { 
            $a = strtolower($a); 
            $b = strtolower($b); 
            if ($a > $b) { 
                return 1; 
            } else { 
                return $a == $b ? 0 : -1; 
            } 
        });

        if (count($analyzes) > 0) {
            $this->createLevel2('Files counts');
            $resultsCount = new \Report\Content\FilesResultCounts();
            $resultsCount->setValues($fileList);
            $resultsCount->collect();
            $this->addContent('FilesHashTableLinked', $resultsCount);

            foreach($fileList as $file => $results) {
                if (!isset($analyzesList[$file])) { continue; }
                if ( empty($analyzesList[$file])) { continue; }

                $this->createLevel2($file);
                $this->addContent('Text', 'File : '.$file, 'textLead');
 
                $fileAnalyzers = new \Report\Content\FileAnalyzers();
                $fileAnalyzers->setValues($analyzesList[$file]);
                $fileAnalyzers->setFilename($file);
                $fileAnalyzers->collect();
                $this->addContent('Horizontal', $fileAnalyzers, 'horizontalForFiles');
            }
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Application
/////////////////////////////////////////////////////////////////////////////////////
        
        $this->createLevel1('Application');
        $this->createLevel2('Appinfo()');
        $this->addContent('Text', <<<TEXT
This is an overview of your application. 

Ticked <i class="icon-ok"></i> information are features used in your application. Non-ticked are feature that are not in use in the application.
Crossed <i class="fa fa-times-circle-o"></i> information were not tested.

TEXT
);
        $this->addContent('Tree', 'Appinfo');

        $this->createLevel2('Directive');
        $this->addContent('Text', <<<TEXT
This is an overview of the recommended directives for your application. 
The most important directives have been collected here, for a quick review. 
The whole list of directive is available as a link to the manual, when applicable. 

When an extension is missing from the list below, either it as no specific configuration directive, 
or it is not used by the current code. 

TEXT
);
        $this->addContent('Directives', 'Directives');

        $composerList = new \Report\Content\ComposerList();
        $composerList->collect();
        if ($composerList->hasResults()) {
            $this->createLevel2('Composer');
            $this->addContent('Text', <<<TEXT
This is the list of the classes, interfaces or traits used in the application. 
TEXT
);
            $this->addContent('Horizontal', $composerList, 'composer');
        }

        $directiveList = new \Report\Content\DirectivesList();
        $directiveList->collect();
        if ($directiveList->hasResults()) {
            $this->createLevel2('Altered Directives');
            $this->addContent('Text', <<<TEXT
This is an overview of the directives that are modified inside the application's code. 

TEXT
);
            $this->addContent('Horizontal', $directiveList, 'directive');
        } 

        $composerList = new \Report\Content\ComposerList();
        $composerList->collect();
        if ($composerList->hasResults()) {
            $this->createLevel2('Composer');
            $this->addContent('Text', <<<TEXT
This is the list of the classes, interfaces or traits used in the application. 
TEXT
);
            $this->addContent('Horizontal', $composerList, 'composer');
        }

        // List of dynamic calls
        $analyzer = \Analyzer\Analyzer::getInstance('Structures/DynamicCalls');
        $this->createLevel2('Dynamic code');
        $this->addContent('Text', 'This is the list of dynamic call. They are not checked by the static analyzer, and the analysis may be completed with a manual check of that list.', 'textLead');
        if ($analyzer->hasResults()) {
            $this->addContent('Horizontal', $analyzer);
        } else {
            $text = <<<'TEXT'
No dynamic calls where found in the code. Dynamic calls may be one of the following : 
<ul>
    <li>Constant<br />
    <ul>
        <li>define('CONSTANT_NAME', $value);</li>
        <li>constant('Constant name');</li>
    </ul></li>

    <li>Variables<br />
    <ul>
        <li>$$variablevariable</li>
        <li>${$variablevariable}</li>
    </ul></li>

    <li>Properties<br />
    <ul>
        <li>$object->$propertyName</li>
        <li>$object->{$propertyName}</li>
        <li>$object->{'property'.'Name'}</li>
    </ul></li>

    <li>Methods<br />
    <ul>
        <li>$object->$methodName()</li>
        <li>call_user_func(array($object, $methodName), $arguments)</li>
    </ul></li>

    <li>Static Constants<br />
    <ul>
        <li>constant('StaticClass::ConstantName');</li>
    </ul></li>

    <li>Static Properties<br />
    <ul>
        <li>$class::$propertyName</li>
        <li>$class::{$propertyName}</li>
        <li>$class::{'property'.'Name'}</li>
    </ul></li>

    <li>Static Methods<br />
    <ul>
        <li>$class::$methodName()</li>
        <li>call_user_func(array('Class', $methodName), $arguments)</li>
    </ul></li>

</ul>

TEXT;
            $this->addContent('Text', $text);
        }
        
        // Stats        
        $this->createLevel2('Stats');
        $this->addContent('Text', <<<TEXT
These are various stats of different structures in your application.

TEXT
);
        $this->addContent('SectionedHashTable', 'AppCounts');

        // Global list
        $analyzer = \Analyzer\Analyzer::getInstance('Structures/GlobalInGlobal');
        if ($analyzer->hasResults()) {
            $this->createLevel2('Global variable list');
            $this->addContent('Text', <<<TEXT
Here are the global variables, including the implicit ones : any variable that are used in the global scope, outside methods, are implicitely globals.

TEXT
);
            $this->addContent('Horizontal', $analyzer);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Custom analyzers
/////////////////////////////////////////////////////////////////////////////////////
        
        $analyzer = \Analyzer\Analyzer::getInstance('Classes/AvoidUsing');

        if ($analyzer->hasResults()) {
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

            $analyzer = \Analyzer\Analyzer::getInstance('Classes/AvoidUsing');
            $this->addContent('Horizontal', $analyzer);
        }

/////////////////////////////////////////////////////////////////////////////////////
/// Annexes
/////////////////////////////////////////////////////////////////////////////////////
        $this->createLevel1('Annexes');

        // Definition for the analyzers
        $this->createLevel2('Documentation');
        $this->addContent('Definitions', $definitions, 'annexes');

        // List of processed files
        $this->createLevel2('Processed files');
        $this->addContent('Text', 'This is the list of processed files. Any file that is in the project, but not in the list below was omitted in the analyze. 
        
This may be due to configuration file, compilation error, wrong extension (including no extension). ', 'textLead');

        $this->addContent('SimpleTable', 'ProcessedFileList', 'oneColumn');

        // List of processed files
        $this->createLevel2('Non-processed files');
        $this->addContent('Text', 'This is the list of non-processed files. The following files were found in the project, but were omitted as requested in the config.ini file.', 'textLead');
        $content = $this->getContent('NonprocessedFileList');
        $content->collect();
        if ($content->hasResults()) {
            $this->addContent('Text', 'This is the list of non-processed files. The following files were found in the project, but were omitted as requested in the config.ini file.', 'textLead');
            $this->addContent('SimpleTable', $content, 'oneColumn');
        } else {
            $this->addContent('Text', 'All files and folder were used');
        }

        // List of external libraries
        $this->createLevel2('External libraries');
        $this->addContent('Text', 'This is the list of ignored external libraries. Those libraries are ignored as they are independant projects, and their analyze would be useless. 
        
The external library list is not exhaustive : it is mainly made to reduce the load of the analyze, by avoiding frequently used libraries. Feel free to submit others libraries to add to the list.', 'textLead');
        $content = $this->getContent('ExternalLibraries');
        $content->collect();
        if ($content->hasResults()) {
            $this->addContent('SimpleTable', $content, 'externalLibraries');
        } else {
            $this->addContent('Text', 'No external libraries found. Note that this tool is made to avoid analyzing code that are not part of this code.');
        }

        // List of used analyzers
        $this->createLevel2('Analyzers');
        $this->addContent('Text', 'This is the list of analyzers that were run.', 'textLead');
        $this->addContent('SimpleTable', 'UsedAnalyzerList', 'usedAnalyzerList');

        // About this report
        $this->createLevel2('About This Report');
        $aboutDevoops = <<<Devoops
            This report has been build, thanks to the following other Open Source projects. 
            
			<div class="about-inner">
				<h3 class="page-header">Devoops</h4>
				<p>By the DevOOPS team : Open-source admin theme for you.</p>
				<p>Homepage - <a href="http://devoops.me" target="_blank">http://devoops.me</a></p>
				<p>Email - <a href="mailto:devoopsme@gmail.com">devoopsme@gmail.com</a></p>
				<p>Twitter - <a href="http://twitter.com/devoopsme" target="_blank">http://twitter.com/devoopsme</a></p>

				<h3 class="page-header">jQuery</h4>
				<p>By the jQuery Foundation</p>
				<p>Homepage - <a href="http://jquery.com/" target="_blank">http://jquery.com/</a></p>
				<p>Twitter - <a href="https://twitter.com/jQuery" target="_blank">https://twitter.com/jQuery</a></p>
			</div>
Devoops;
        $this->addContent('Text', $aboutDevoops);
    }
}

?>
