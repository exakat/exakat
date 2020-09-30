<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
$docs = new Docs();
$docs->buildDocs();

class Docs {
    const S_CRITICAL = 'Critical';
    const S_MAJOR    = 'Major';
    const S_MINOR    = 'Minor';
    const S_NOTE     = 'Note';
    const S_NONE     = 'None';

    const T_NONE    = 'None';    //'0';
    const T_INSTANT = 'Instant'; //'5';
    const T_QUICK   = 'Quick';   //30';
    const T_SLOW    = 'Slow';    //60';
    const T_LONG    = 'Long';    //360';

    const TIMETOFIX = array('T_INSTANT' => 'Instant (5 mins)',
                            'T_QUICK'   => 'Quick (30 mins)',
                            'T_SLOW'    => 'Slow (1 hour)',
                            'T_LONG'    => 'Long (4 hours)' );

    const SEVERITIES = array('S_CRITICAL' => 'Critical',
                            'S_MAJOR'     => 'Major',
                            'S_MINOR'     => 'Minor',
                            'S_NOTE'      => 'Note',
                            'S_NONE'      => '',
                             );

    const PRECISIONS = array('P_VERY_HIGH' => 'Very high',
                             'P_HIGH'      => 'High',
                             'P_MEDIUM'    => 'Medium',
                             'P_LOW'       => 'Low',
                             'P_NONE'      => 'Unknown',
                            );

    private $analyzers = null;
    
    private $ini_list      = array();
    private $report_list   = array();
    private $docs_list     = array();
    private $attributes    = array();
    private $entries       = array();
    private $rules         = array();

    private $analyzer_count         = -1;
    private $extension_list         = array();
    private $extension_list_rst     = '';
    private $library_list           = array();
    private $reports_list           = '';
    private $external_services_list = '';
    private $analyzer_introduction  = '';
    private $url_list               = '';
    private $applications           = '';
    private $applications_names     = array();
    private $issues_examples        = array();
    private $parameter_list         = array();
    private $glossary               = array();
    private $text                   = '';
    private $ini_ruleset_config      = '';
    private $php_error_list         = array();
    private $exakat_extension_list  = '';
    private $exakat_extension_det   = '';
    private $parametered_analysis   = '';
    private $list_atoms             = array();
    private $details_atoms          = array();
    private $list_steps             = array();
    
    private $exakat_site            = '';
    private $exakat_version         = '';
    private $exakat_build           = '';
    private $exakat_date            = '';

    private $rulesets = array('Analyze',
                              'CI-checks',
                              'CompatibilityPHP80',
                              'CompatibilityPHP74',
                              'CompatibilityPHP73',
                              'CompatibilityPHP72',
                              'CompatibilityPHP71',
                              'CompatibilityPHP70',
                              'CompatibilityPHP56',
                              'CompatibilityPHP55',
                              'CompatibilityPHP54',
                              'CompatibilityPHP53',
                              'Security',
                              'Performances',
                              'Dead code',
                              'Coding Conventions',
                              'Suggestions',
                              'ClassReview',
                              'LintButWontExec',
                              'Top10',
                              'Semantics',
                              'Typechecks',
                              'Rector',
                              'php-cs-fixable',
                             );

    private $extras = array( 
                 'switch()'                       => 'https://www.php.net/manual/en/control-structures.switch.php',
                 'for()'                          => 'https://www.php.net/manual/en/control-structures.for.php',
                 'foreach()'                      => 'https://www.php.net/manual/en/control-structures.foreach.php',
                 'while()'                        => 'https://www.php.net/manual/en/control-structures.while.php',
                 'do..while()'                    => 'https://www.php.net/manual/en/control-structures.do.while.php',
   
                 'die'                            => 'https://www.php.net/die',
                 'exit'                           => 'https://www.www.php.net/exit',
                 'isset'                          => 'https://www.www.php.net/isset',
                 'break'                          => 'https://www.php.net/manual/en/control-structures.break.php',
                 'continue'                       => 'https://www.php.net/manual/en/control-structures.continue.php',
                 'instanceof'                     => 'https://www.php.net/manual/en/language.operators.type.php',
                 'insteadof'                      => 'https://www.php.net/manual/en/language.oop5.traits.php',
                     
                 '**'                             => 'https://www.php.net/manual/en/language.operators.arithmetic.php',
                 '...'                            => 'https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list',
                 '@'                              => 'https://www.php.net/manual/en/language.operators.errorcontrol.php',
                 '$_GET'                          => 'https://www.php.net/manual/en/reserved.variables.get.php',
                 '$_POST'                         => 'https://www.php.net/manual/en/reserved.variables.post.php',
                 '$_REQUEST'                      => 'https://www.php.net/manual/en/reserved.variables.request.php',
                 '$_ENV'                          => 'https://www.php.net/manual/en/reserved.variables.env.php',
                 '$HTTP_RAW_POST_DATA'            => 'https://www.php.net/manual/en/reserved.variables.httprawpostdata.php',
                 '$this'                          => 'https://www.php.net/manual/en/language.oop5.basic.php',
                 'parent'                         => 'https://www.php.net/manual/en/language.oop5.paamayim-nekudotayim.php',
                 'self'                           => 'https://www.php.net/manual/en/language.oop5.paamayim-nekudotayim.php',
                 'static'                         => 'https://www.php.net/manual/en/language.oop5.static.php',
                  
                 '__construct'                  => 'https://www.php.net/manual/en/language.oop5.decon.php',
                 '__destruct'                   => 'https://www.php.net/manual/en/language.oop5.decon.php',
                  
                 '__call'                       => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__callStatic'                 => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__get'                        => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__set'                        => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__isset'                      => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__unset'                      => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__sleep'                      => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__wakeup'                     => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__toString'                   => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__invoke'                     => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__set_state'                  => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__clone'                      => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 '__debugInfo'                  => 'https://www.php.net/manual/en/language.oop5.magic.php',
                 
                 'ArrayAccess'                  => 'https://www.php.net/manual/en/class.arrayaccess.php',
                 'ArrayObject'                  => 'https://www.php.net/manual/en/class.arrayobject.php',
                 'SimpleXMLElement'             => 'https://www.php.net/manual/en/class.simplexmlelement.php',
                 'Throwable'                    => 'https://www.php.net/manual/en/class.throwable.php',
                 'Generator'                    => 'https://www.php.net/manual/en/class.generator.php',
                 'Closure'                      => 'https://www.php.net/manual/en/class.closure.php',
                 'Traversable'                  => 'https://www.php.net/manual/en/class.traversable.php',
                 'ParseError'                   => 'https://www.php.net/manual/en/class.parseerror.php',
                 'DivisionByZeroError'          => 'https://www.php.net/manual/fr/class.divisionbyzeroerror.php',
                 'NULL'                         => 'https://www.php.net/manual/en/language.types.null.php',
                 'Datetime'                     => 'https://www.php.net/manual/en/class.datetime.php',
                 'DatetimeImmutable'            => 'https://www.php.net/manual/en/class.datetimeimmutable.php',
                 'DatetimeInterface'            => 'https://www.php.net/manual/en/class.datetimeinterface.php',
                 'Datetimezone'                 => 'https://www.php.net/manual/en/class.datetimezone.php',
                 'Datetimeinterval'             => 'https://www.php.net/manual/en/class.dateinterval.php',
                 'Dateperiod'                   => 'https://www.php.net/manual/en/class.dateperiod.php',
                 'WeakReference'                => 'https://www.php.net/manual/en/class.weakreference.php',
                 'Serializable'                 => 'https://www.php.net/manual/en/class.serializable.php',
                 'ReflectionReference'          => 'https://www.php.net/manual/en/class.reflectionreference.php',
                 
                 '__FILE__'                   => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__DIR__'                    => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__LINE__'                   => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__CLASS__'                  => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__METHOD__'                 => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__NAMESPACE__'              => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__TRAIT__'                  => 'https://www.php.net/manual/en/language.constants.predefined.php',
                 '__FUNCTION__'               => 'https://www.php.net/manual/en/language.constants.predefined.php',

                 'track_errors'               => 'https://www.php.net/manual/en/errorfunc.configuration.php#ini.track-errors',
                 'max_execution_time'         => 'https://www.php.net/manual/en/errorfunc.configuration.php#ini.max-execution-time',

    );

    function __construct() {
        $this->analyzers = new \Sqlite3('data/analyzers.sqlite');
    }
    
    public function buildDocs() {
        shell_exec('rm docs/*.rst');
        shell_exec('cp docs/src/*.rst docs/');
        shell_exec('cp docs/src/images/*.png docs/images/');

        $this->getAnalyzerCount();
        $this->getIniList();
        $this->getPhpError();//PHP_ERROR_MESSAGES
        $this->getExtensions();
        $this->getReportList();
        $this->getRulesetList();
        $this->getExternalServicesList();
        $this->generateAnalyzerList();
        $this->makeUrlList();
        $this->getExternalLibrary();
        $this->prepareIniRulesets();

        $this->prepareExakatExtensions();
        $this->prepareDevelopment();

        $this->getExakatInfo();
        $this->build_reports();
        $this->prepareText();
        $this->prepareParameterList();
        $this->makeApplicationsLink();

        $this->getAttributesArray();

        $this->replaceAttributes();
        $this->replaceSpecials();

        $this->finishGlossary();
    }
    
    private function getAnalyzerCount() {
        $res = $this->analyzers->query('SELECT COUNT(*)
                FROM categories c
                JOIN analyzers_categories ac
                    ON c.id = ac.id_categories
                JOIN analyzers a
                    ON a.id = ac.id_analyzer
                WHERE c.name = "Analyze"');
        $this->analyzer_count = $res->fetchArray(\SQLITE3_NUM)[0];
    }
    
    private function getPhpError() {
        $list = array();
        foreach($this->ini_list as $file) {
            $ini = parse_ini_file($file);
        
            if (isset($ini['phpError'])) {
                foreach($ini['phpError'] as $phpError) {
                    $list[] = $this->rst_link(str_replace('`', '', $phpError) , $this->rst_anchor($ini['name']));
                }
            }
        }
        sort($list); // alphabetical sort
        
        $this->php_error_list = count($list)." PHP error message detailled : \n\n* ".implode("\n* ", $list)."\n\n";
    }
    
    private function getIniList() {
        $this->extension_list = glob('./human/en/Extensions/Ext*.ini');
        $this->ini_list       = glob('./human/en/*/*.ini');
        $this->report_list    = glob('./human/en/Reports/*.ini');
        $this->docs_list      = glob('./docs/*.rst');
    }
    
    private function getExtensions() {
        $extension_list = array();
        foreach($this->extension_list as $f) {
            $ini = parse_ini_file($f);
    
            // We take the first URL that we encounter.
            if (preg_match('/<(https?:.*?)>/', $ini['description'], $r)) {
                $extension_list[] = '* `'.$ini['name'].' <'.$r[1].'>`_';
            } else {
                $extension_list[] = '* '.$ini['name'];
            }
        }
        $this->extension_list_rst = implode("\n", $extension_list);
    }

    private function getExternalLibrary() {
        $json = json_decode(file_get_contents('data/externallibraries.json'));
        foreach( (array) $json as $library) {
            if (empty($library->homepage)) {
                $library_list[] = '* '.$library->name;
            } else {
                $library_list[] = '* `'.$library->name.' <'.$library->homepage.'>`_';
            }
        }
        $this->library_list = implode("\n", $library_list);
    }

    private function getReportList() {
        include __DIR__.'/../../library/Exakat/Reports/Reports.php' ;
        $reports_list = \Exakat\Reports\Reports::$FORMATS;
        $this->reports_list = '  * '.implode("\n  * ", $reports_list)."\n";
    }

    private function getRulesetList() {
        $res = $this->analyzers->query('SELECT name FROM categories c ORDER BY name');
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $themes_list[] = '* '.$row[0];
        }
        $this->rulesets_list = implode("\n", $themes_list);
    }

    private function getExternalServicesList() {
        $external_services_list = array();
        $json = json_decode(file_get_contents(__DIR__.'/../../data/serviceConfig.json'));
        foreach( (array) $json as $name => $service) {
            $external_services_list[] = "* `$name <$service->homepage>`_ - ".implode(', ', $service->file);
        }
        $this->external_services_list = implode("\n", $external_services_list);
    }
    
    private function generateAnalyzerList() {
        $files = glob('./human/en/*/*.ini');
        
        $sqlite = new \Sqlite3('data/analyzers.sqlite');
        
        $versions = array();
        foreach($files as $file) {
            $folder = basename(dirname($file));
            if ($folder === 'Reports' || $folder === 'DSL' || $folder === 'Rulesets') { 
                continue; 
            }

            $analyzer = basename($file, '.ini');
            $name = "$folder/$analyzer";
            
            $res = $this->analyzers->query(<<<SQL
SELECT GROUP_CONCAT(c.name, ', ') AS categories FROM analyzers a
    JOIN analyzers_categories ac
        ON ac.id_analyzer = a.id
    JOIN categories c
        ON c.id = ac.id_categories
    WHERE
        a.folder = "$folder" AND
        a.name   = "$analyzer" AND
        (c.name NOT IN ('All'))
SQL
    );
            $row = $res->fetchArray(\SQLITE3_ASSOC);

            // Only handling 2 parameters max
            $ini = parse_ini_file($file, true);
            for($i = 1; $i < 3; ++$i) {
                if (isset($ini["parameter$i"]) && $row['categories'] != 'Appinfo') {
                    $this->parametered_analysis .= $this->rst_link($ini['name'], $this->rst_anchor($ini['name'])).PHP_EOL.
'  + '.$ini["parameter$i"]['name'].' : '.($ini["parameter$i"]['default'] ?? '').PHP_EOL.PHP_EOL.
'    + '.$ini["parameter$i"]['description'].PHP_EOL;
                }
            }
            
            
            if (empty($ini['exakatSince'])) {
                print "No exakatSince in {$file}\n";
                continue;
            }
//            * :ref:`No Hardcoded Path <no-hardcoded-path>`
            if (isset($versions[$ini['exakatSince']])) {
                $versions[$ini['exakatSince']][] = $ini['name'].' ('.$name.' ; '.$row['categories'].')';
            } else {
                $versions[$ini['exakatSince']] = array($ini['name'].' ('.$name.')');
            }
        }
        uksort($versions, function ($a, $b) { return version_compare($b, $a); });
        
        $list = "\n";
        foreach($versions as $version => $analyzers) {
            $list .= "* $version\n\n";
            sort($analyzers);
            $list .= '  * '.implode("\n  * ", $analyzers)."\n\n";
        }
        $list .= "\n";
    
        $this->analyzer_introduction = $list;
    }
    
    private function makeUrlList() {
        $raw = explode("\n", shell_exec('grep -r \'>\`_\' '.__DIR__.'/../../docs/src/'));
        $urls = array();
        foreach($raw as $line) {
            preg_match_all('/(`.*?>`_)/s', $line, $r);
            $urls[] = $r[1];
        }
        
        $urls = array_merge(...$urls);

        foreach($this->ini_list as $file) {
            $ini = parse_ini_file($file);
        
            if (preg_match('/(`[^`]*?>`_)/s', $ini['description'], $r)) {
                $urls[] = $r[1];
            }
        }
        
        $urls = array_keys(array_count_values($urls));
        
        uasort($urls, function($a, $b) { 
            preg_match('/`(.+) </', $a, $aa);
            preg_match('/`(.+) </', $b, $bb);
            if (empty($aa[1])) {
                print "Empty link : $a\n";
            } elseif (empty($bb[1])) {
                print "Empty link : $b\n";
            } elseif ($aa[1] == $bb[1]) {
                print "Double link : $a / $b\n";
            }
            
            return strtolower($a) <=> strtolower($b); 
        });
        
        $this->url_list = "* ".implode("\n* ", $urls)."\n";
    }
    
    private function getExakatInfo() {
        /// URL
        $this->exakat_site = 'https://www.exakat.io/';
        
        $php = file_get_contents(__DIR__.'/../../library/Exakat/Exakat.php');
        //    const VERSION = '1.0.3';
        preg_match('/const VERSION = \'([0-9\.]+)\';/is', $php, $r);
        $this->exakat_version = $r[1];
        
        $py = file_get_contents('docs/conf.py');
        $py = preg_replace('/version = u\'\d\.\d.\d\'/', 'version = u\''.$this->exakat_version.'\'', $py);
        file_put_contents('docs/conf.py', $py);
        
        //    const BUILD = 661;
        preg_match('/const BUILD = ([0-9]+);/is', $php, $r);
        $this->exakat_build = $r[1];
        
        $this->exakat_date = date('r', filemtime(__DIR__.'/../../library/Exakat/Exakat.php'));
    }
    
    private function getAttributesArray() {
        // More to come,and automate collection too
        $this->attributes = array(
                            'ANALYZERS_COUNT'        => $this->analyzer_count,
                            'EXTENSION_LIST'         => $this->extension_list_rst,
                            'LIBRARY_LIST'           => $this->library_list,
                            'ANALYZER_INTRODUCTION'  => $this->analyzer_introduction,
                            'EXTERNAL_SERVICES_LIST' => $this->external_services_list,
                            'REPORTS_LIST'           => $this->reports_list,
                            'RULESETS_LIST'          => $this->rulesets_list,
                            'URL_LIST'               => $this->url_list,
                            'EXAKAT_VERSION'         => $this->exakat_version,
                            'EXAKAT_BUILD'           => $this->exakat_build,
                            'EXAKAT_SITE'            => $this->exakat_site,
                            'EXAKAT_DATE'            => $this->exakat_date,
                            'APPLICATIONS'           => $this->applications,
                            'ISSUES_EXAMPLES'        => implode('', $this->issues_examples),
                            'PARAMETER_LIST'         => implode('', $this->parameter_list),
                            'INI_RULESETS'           => $this->ini_ruleset_config,
                            'PHP_ERROR_MESSAGES'     => $this->php_error_list,
                            'EXAKAT_EXTENSION_LIST'  => $this->exakat_extension_list,
                            'EXTENSION_DETAILS'      => $this->exakat_extension_det,
                            'PARAMETERED_ANALYSIS'   => $this->parametered_analysis,
                            'LIST_ATOMS'             => $this->list_atoms,
                            'DETAILS_ATOMS'          => $this->details_atoms,
                            'LIST_STEPS'             => $this->list_steps,
                            );
    }

    private function makeTable(array $array) {
        foreach($array as &$row) {
            foreach($row as &$r) {
                $r = explode(PHP_EOL, $r);
            }
            unset($r);
        }
        unset($row);

        // padding the missing lines
        foreach($array as &$row) {
            $count = 1;
            foreach($row as $r) {
                $count = max($count, count($r));
            }
            
            if ($count > 1) {
                foreach($row as &$r) {
                    $r = array_pad($r, $count, '');
                }
                unset($r);
            }
        }
        unset($row);

        $sizes = array();
        foreach(array_keys($array[0]) as $col) {
            $values = array_merge(...array_column($array, $col));
            foreach($values as &$value) {
                $value = explode(PHP_EOL, $value);
                $value = array_reduce($value, function($carry, $item) { return strlen($carry) > strlen($item) ? $carry : $item;});
            }
            $strlens = array_map('strlen', $values);
            $sizes[] = max($strlens);
        }

        $separator = '+'.implode('+', array_map(function($x) { return str_pad('', $x + 2, '-'); }, $sizes)).'+'.PHP_EOL;

        $return = $separator;
        foreach($array as $row) {
            foreach(array_keys($row[0]) as $w) {
                $str = '|';
                foreach(array_keys($row) as $col) {
                    $str .= ' '.str_pad($row[$col][$w] ?? 's', $sizes[$col], ' ').' |';
                }
                $return .= $str.PHP_EOL;
            }
            $return .= $separator;
        }
    
        return $return;
    }
    
    private function makeApplicationsLink() {
        include __DIR__.'/applications.php';
        
        ksort($this->applications_names);
    
        $names = array_map(function($x) use ($applications) { 
            if (isset($applications[$x])) { 
                $x = "`$x <".$applications[$x]['url'].">`_";
            } else { 
                print "Missing url for $x\n"; 
            } 
            
            return "* $x\n"; }, array_keys($this->applications_names));
        $this->applications = implode('', $names);
    }

    private function build_reports() {
        $file = file_get_contents(__DIR__.'/../../docs/Reports.rst');
        
        $reportList = array();
        $reportSection = array();
        foreach($this->report_list as $reportFile) {
            $reportIni = parse_ini_file($reportFile);
            
            $reportList[] = '`'.$reportIni['name'].'`_';
    
            $section = $reportIni['name']."\n".str_repeat('_', strlen($reportIni['name']))."\n\n";
            $description = $this->internalLink($reportIni['description']);
            $section .= $reportIni['mission']."\n\n".$description."\n\n";

            if (!isset($reportIni['examples'])) {
                print "No examples for $reportFile\n";
                continue;
            }
            foreach($reportIni['examples'] as $id => $example) {
                if (preg_match('/\.png$/', $example)) {
                    $section .= ".. image:: images/$example
    :alt: Example of a $reportIni[name] report ($id)

";
                } elseif (preg_match('/\.txt$/', $example)) {
                    $exampleTxt = file_get_contents('./docs/src/images/'.$example);
                    $exampleTxt = '    '.str_replace("\n", "\n    ", $exampleTxt);
                    $section .= "\n::

$exampleTxt

";
                }
            }
            
            if (!empty($reportIni['depends'][0])) {
                if (count($reportIni['depends']) === 1) {
                    $section .= $reportIni['name']. ' includes the report from another other report : '.implode(', ', $reportIni['depends']).".\n\n";
                } else {
                    sort($reportIni['depends']);
                    $section .= $reportIni['name']. ' includes the report from '.count($reportIni['depends']).' other reports : '.implode(', ', $reportIni['depends']).".\n\n";
                }
            }
    
            $section .= $reportIni['name']. " is a $reportIni[type] report format.\n\n";

            if (!empty($reportIni['arbitrarylist'])) {
                $section .= $reportIni['name']. " accepts any arbitrary list of results.\n\n";
            } elseif (empty($reportIni['themes'][0])) {
                $section .= $reportIni['name']. " doesn't depend on themes.\n\n";
            } elseif (count($reportIni['themes']) === 1) {
                $section .= $reportIni['name']. " depends on the following theme : ".array_pop($reportIni['themes']).".\n\n";
            } else {
                $c = count($reportIni['themes']);
                $section .= $reportIni['name']. " depends on the following $c themes : ".implode(', ', $reportIni['themes']).".\n\n";
            }
    
            $reportSection[] = $section;
        }
        
        $reportList = '* '.implode("\n* ", $reportList).PHP_EOL;
        $reportSection = implode('', $reportSection).PHP_EOL;
        
        $file = str_replace('REPORT_LIST', $reportList, $file);
        $file = str_replace('REPORT_DETAILS', $reportSection, $file);
        
        file_put_contents('./docs/Reports.rst', $file);
    }

    private function rst_anchor($name) {
        return str_replace(array(' ','_',':'),array('-','\\_','\\:'),strtolower($name));
    }
    
    private function rst_anchor_def($name) {
        return '.. _'.$this->rst_anchor($name).":\n\n";
    }
    
    private function rst_escape($string) {
        $r = str_replace(array('::', '**='),array('\\:\\:', '\\*\\*\\='), $string);

        $r = preg_replace_callback('/<\?php(.*?)\?>/is',function ($r) {
            $code = preg_replace('/`([^ ]+?) .*?`_/','$1',$r[0]);
            $code = str_replace('\\:\\:', '::', $code);
            $rst = ".. code-block:: php\n\n   ".str_replace("\n","\n   ",$code)."\n";
            return $rst;
        }, $r);

        $r = preg_replace_callback('/\s*<\?literal(.*?)\?>/is',function ($r) {
            $rst = "::\n\n   ".str_replace("\n","\n   ",$r[1])."\n";
            return $rst;
        }, $r);

        return $r;
    }
    
    private function rst_link($title, $link = '') {
        if (empty($link)) {
           if (strpos($title,' ') === false) {
                return ':ref:`'.$this->rst_escape($title).'`';
            } else {
                $escapeTitle = $this->rst_anchor($title);
                return ':ref:`'.$this->rst_escape($title).' <'.$escapeTitle.'>`';
            }
        } else {
            return ':ref:`'.$this->rst_escape($title).' <'.$link.'>`';
        }
    }
    
    private function rst_level($title,$level = 1) {
        $levels = array(1 => '=',2 => '-',3 => '#',4 => '+');
        $escapeTitle = $this->rst_escape($title);
        return $this->rst_anchor_def($title).$escapeTitle."\n".str_repeat($levels[$level],strlen($escapeTitle))."\n";
    }

    private function glossary($title, $description) {
        $chunks = array_chunk(array_keys($this->entries), 1000);
        
        $alts = array();
        foreach($chunks as $chunk) {
            $alt = implode('|',$chunk);
            $alt = str_replace(array('*','(',')', '$', '.'), array('\\*','\(','\)', '\\$', '\\.'), $alt);
            $alts []= '/([^a-zA-Z_`])('.$alt.')(\(?\)?)(?=[^a-zA-Z_=])/is';
        }
        
        $cbGlossary = function ($r) use ($title) {
            $letter = strtoupper($r[2][0]);
            $this->glossary[$letter][$r[2]][':ref:`'.$title.' <'.$this->rst_anchor($title).'>`'] = 1;
            
            if (isset($this->entries[$r[2]])) {
                $url = $this->entries[$r[2]];
                return $r[1].'`'.$r[2].$r[3].' <'.$url.'>`_';
            } elseif (isset($this->entries[strtolower($r[2])])) {
                $url = $this->entries[strtolower($r[2])];
                return $r[1].'`'.$r[2].$r[3].' <'.$url.'>`_';
            } else {
                return $r[0];
            }
    
        };
        
        // preserve code to avoid remplacement inside them
        $codes = array();
        $saveCode = function ($x) use (&$codes) { 
            $codes[] = $x[0]; 
            return "----".(count($codes) - 1)."----"; 
        };
        $description = preg_replace_callback('/<\\?php.*?\\?>/s', $saveCode, $description);

        $description = preg_replace_callback($alts, $cbGlossary, ' '.$description);

        $restoreCode = function ($x) use ($codes) {
            return $codes[$x[1]];
        };
        $description = preg_replace_callback('/----(\d+)----/s', $restoreCode, $description);

        return $description;
    }

    private function build_analyzer_doc($analyzer, $a2themes) {
        $name = $analyzer;
        $ini = file_get_contents("./human/en/$analyzer.ini");
        if (preg_match_all('/\[example\d\]/', $ini, $r)) {
            $distinct = array_count_values($r[0]);
            $distinct = array_filter($distinct, function ($x) { return $x > 1;});
            if (!empty($distinct)) {
                print "$analyzer : double numbered examples\n";
                print_r($distinct);
            }
        }
        $ini = parse_ini_file("./human/en/$analyzer.ini", true);
        $commandLine = $analyzer;
        
        $desc = $this->glossary($ini['name'], $ini['description']);
        $desc = $this->internalLink($desc);

        if (isset($ini['modifications'])) {
            if (!is_array($ini['modifications'])) {
                $ini['modifications'] = array($ini['modifications']);
                print "In $analyzer, modifications is not an array\n";
            }
            
            $desc .= "\n\nSuggestions\n^^^^^^^^^^^\n\n* ".implode("\n* ", $ini['modifications'])."\n\n\n";
        } else {
            print "Missing modifications : ./human/en/$analyzer.ini\n";
        }
        $desc = trim($this->rst_escape($desc));
        $desc = preg_replace_callback('/See also .*?`_\./s', function($x) {
            if (strpos($x[0], PHP_EOL) === false) {
                return $x[0];
            }
            
            $res = preg_replace('/\s+/', ' ', $x[0]);

            return $res;
        }, $desc);
        
        if (empty($ini['clearphp'])) {
            $clearPHP = '';
        } else {
            $clearPHP = "`$ini[clearphp] <https://github.com/dseguy/clearPHP/tree/master/rules/$ini[clearphp].md>`__";
        }

        if (isset($a2themes[$name])) {
            $c = array_map(array($this, 'rst_link'),$a2themes[$name]);
            $rulesets = implode(', ',$c);
        } else {
            $rulesets = 'none';
        }

        $examples = array();
        $issues_examples_section_list = array();
        $previous = '';
        for($i = 0; $i < 10; ++$i) {
            if (isset($ini["example$i"])) {
                $issues_examples_section = '';
                if (!isset($ini['example'.$i]['project'])) {
                    print 'Missing "project" in '.$analyzer.".ini\n";
                }
                $label = $this->rst_anchor($ini['example'.$i]['project'].'-'.str_replace('/', '-', strtolower($analyzer)));
                
                $examples[] = ':ref:`'.$label.'`';
                $code = "    ".str_replace("\n", "\n    ", trim($ini['example'.$i]['code']));
                $section = $ini['example'.$i]['project']."\n".str_repeat('^', strlen($ini['example'.$i]['project']));
                $explain = $ini['example'.$i]['explain'];
                $file = $ini['example'.$i]['file'];
                $line = $ini['example'.$i]['line'];
                if ($previous === "$file::$line") {
                    print "Suspicious identical examples file/line in '$analyzer'\n";
                }
                $previous = "$file::$line";
                $analyzer_anchor = $this->rst_anchor($ini['name']);
            
                if (empty($issues_examples_section_list)){
                    $issues_examples_section = $ini['name']."\n".str_repeat('=', strlen($ini['name']))."\n";
                }

                $issues_examples_section .= <<<SPHINX

.. _$label:

$section

:ref:`$analyzer_anchor`, in $file:$line. 

$explain

.. code-block:: php

$code


SPHINX;
                $this->applications_names[$ini['example'.$i]['project']] = 1;
                $issues_examples_section_list[] = $issues_examples_section;
            }
        }

        $issues_examples_section = implode(PHP_EOL.'--------'.PHP_EOL.PHP_EOL, $issues_examples_section_list);
        
        $parameters = array();
        for($i = 0; $i < 10; ++$i) {
            if (isset($ini['parameter'.$i])) {
                if (isset($this->parameter_list[$ini['name']])) {
                    $this->parameter_list[$ini['name']][$ini['parameter'.$i]['name']] = $ini['parameter'.$i]['default'] ?? '';
                } else {
                    $this->parameter_list[$ini['name']] = array($ini['parameter'.$i]['name'] => $ini['parameter'.$i]['default'] ?? '') ;
                }

                $parameters[] = [$ini['parameter'.$i]['name'],
                                 $ini['parameter'.$i]['default'] ?? '',
                                 $ini['parameter'.$i]['type'],
                                 $ini['parameter'.$i]['description'],
                                 ];
            }
        }
        
        if (!empty($parameters)) {
            array_unshift($parameters, ["Name", "Default", "Type", "Description"]);
            $desc .= PHP_EOL.PHP_EOL.$this->makeTable($parameters).PHP_EOL;
        }
        
        if (!empty($issues_examples_section)){
            $this->issues_examples[] = $issues_examples_section;
        }
        
        $info = array( array('Short name',  $commandLine),
                       array('Rulesets',      $rulesets),
                      );

        if (empty($ini['phpversion'])) {
            $php = file_get_contents("./library/Exakat/Analyzer/$analyzer.php");
            if (preg_match('/protected .phpVersion = \'(.*?)\';/s', $php, $r)) {
                $info[] = array('Php Version', $r[1]);
            }
        } else {
            $info[] = array('Php Version', $this->readPhpversion($ini['phpversion']));
        }

        if (!empty($ini['severity'])) {
            if (!isset(self::SEVERITIES[$ini['severity']])) {
                print "No such severity as '{$ini['severity']}'\n";
            }
            $info[] = array('Severity',    self::SEVERITIES[$ini['severity']]);
        }

        if (!empty($ini['timetofix'])) {
            if (!isset(self::TIMETOFIX[$ini['timetofix']])) {
                print "No such timetofix as '{$ini['timetofix']}'\n";
            }
            $info[] = array('Time To Fix', self::TIMETOFIX[$ini['timetofix']]);
        }

        if (!empty($ini['precision'])) {
            if (!isset(self::PRECISIONS[$ini['precision']])) {
                print "No such precision as '{$ini['precision']}'\n";
            }
            $info[] = array('Precision', self::PRECISIONS[$ini['precision']]);
        }

        if (!empty($clearPHP)) {
            $info[] = array('ClearPHP', $clearPHP);
        }

        if (!empty($examples)) {
            $info[] = array('Examples', implode(', ', $examples));
        }
        $table = $this->makeTable($info);

        $desc .= PHP_EOL.PHP_EOL.$table.PHP_EOL.PHP_EOL;
        
        return array($desc, $ini['name']);
    }
    
    private function prepareIniRulesets() {
        $rulesetsList = '"'.implode('","',$this->rulesets).'"';

        $query = <<<SQL
SELECT c.name,GROUP_CONCAT(a.folder || "/" || a.name) analyzers  
    FROM categories c
    JOIN analyzers_categories ac
        ON c.id = ac.id_categories
    JOIN analyzers a
        ON a.id = ac.id_analyzer
    WHERE c.name IN ($rulesetsList)
    GROUP BY c.name
SQL;
        $res = $this->analyzers->query($query);
        
        $config = array();
        $list = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $list[] = "`$row[name] <ruleset_ini_".strtolower($row['name']).">`_";
            $analyzers = explode(',', $row['analyzers']);
            sort($analyzers);
            $analyzers = implode(',', $analyzers);
            $ini = @parse_ini_file("./human/en/Rulesets/$row[name].ini");
            if (!isset($ini['description'])) {
                print "Missing ./human/en/Rulesets/$row[name].ini\n";
                $ini['description'] = '';
            }
            $config[] = "\n.. _ruleset_ini_".strtolower($row['name']).":\n\n".$row['name']."\n$ini[description]\n".str_repeat('_', strlen($row['name']))."\n\n| [$row[name]]\n|   analyzer[] = \"".str_replace(',', "\";\n|   analyzer[] = \"", $analyzers)."\";| \n\n\n\n";
        }
        
        $this->ini_ruleset_config = count($list)." rulesets detailled here : \n\n* ".implode("\n* ", $list)."\n\n\n".implode("\n\n", $config);
        print count($list)." rulesets detailled in annex\n";
    }

    private function prepareText() {
        $rulesetsList = '"'.implode('","',$this->rulesets).'"';
        $ext = glob('./human/en/Extensions/Ext*.ini');
        $functions = array();
        foreach($ext as $e) {
            if (preg_match('/Extensions\/Ext(.*?).ini/', $e, $r)) {
                $functions[] = "./data/$r[1].ini";
            }
        }
        $inis = array();
        foreach($functions as $function) { 
            if (file_exists($function)) {
                $ini = parse_ini_file($function);
            } else {
                $function = str_replace('.ini', '.json', $function);
                $ini = json_decode(file_get_contents($function), true);
            }
            if (!isset($ini['functions'])) { continue; }
            if (empty($ini['functions'])) { continue; }
            
            $inis[] = array_filter($ini['functions']);
        }
        $ini = array_merge(...$inis);
        $ini = array_unique($ini);
        
        foreach($ini as &$f) {
            $f .= '()';
        }
        unset($f);
        
        $this->entries = array_flip($ini);
        foreach($this->entries as $f => &$link) {
            $link = 'https://www.php.net/'.substr($f, 0, -2);
        }
        unset($link);
        
        $this->entries = array_merge($this->entries, $this->extras);
        
        $query = 'SELECT a.folder || "/" || a.name AS analyzer,GROUP_CONCAT(c.name) analyzers  
                        FROM categories c
                        JOIN analyzers_categories ac
                            ON c.id = ac.id_categories
                        JOIN analyzers a
                            ON a.id = ac.id_analyzer
                        WHERE c.name IN ('.$rulesetsList.')
                        GROUP BY a.name';
        $res = $this->analyzers->query($query);
        $a2themes = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $a2themes[$row['analyzer']] = explode(',',$row['analyzers']);
        }
        
        $query = 'SELECT c.name,GROUP_CONCAT(a.folder || "/" || a.name) analyzers  
                        FROM categories c
                        JOIN analyzers_categories ac
                            ON c.id = ac.id_categories
                        JOIN analyzers a
                            ON a.id = ac.id_analyzer
                        WHERE c.name IN ('.$rulesetsList.')
                        GROUP BY c.name';
        
        $res = $this->analyzers->query($query);
        $analyzers = array();
        $deja = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $liste = explode(',', $row['analyzers']);
        
            foreach($liste as &$a) {
                if (isset($deja[$a])) { 
                    $name = $deja[$a];
                } else {
                    list($desc, $name) = $this->build_analyzer_doc($a, $a2themes);
                    $deja[$a] = $name;
                    $analyzers[$name] = $desc;
                }
                $a = $this->rst_link($name, $this->rst_anchor($name));
            }
            unset($a);
        
            sort($liste);
            $ini = parse_ini_file("./human/en/Rulesets/$row[name].ini");

            $this->text .= $this->rst_level($row['name'],4)."\n".$ini['description']."\n\nTotal : ".count($liste)." analysis\n\n* ".implode("\n* ",$liste)."\n\n";
        }

        ksort($analyzers);
        foreach($analyzers as $title => $desc) {
            $this->rules []= $this->rst_level($title,3).PHP_EOL.PHP_EOL.$desc.PHP_EOL;
        }
        $this->rules = implode('', $this->rules);
    }
    
    private function replaceAttributes() {
        foreach($this->docs_list as $file) {
            $rst = file_get_contents($file);
             
            $rst = str_replace(array_map(function ($x) { return '{{'.$x.'}}'; }, array_keys($this->attributes)), array_values($this->attributes), $rst);
            if (preg_match_all('/{{(.*?)}}/',$rst,$r)) {
                print "There are ".count($r[1])." missed attributes in \"".basename($file)."\" : ".implode(",",$r[1])."\n\n";
            }
            
            file_put_contents(str_replace('/src/','/',$file),$rst);
        }
    }
    
    private function replaceSpecials() {
        $rst = file_get_contents('./docs/src/Rulesets.rst');
        $date = date('r');
        $hash = shell_exec('git rev-parse HEAD');
        $rst = preg_replace('/.. comment: Rulesets details(.*)$/is',".. comment: Rulesets details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$this->text", $rst);
        print file_put_contents('docs/Rulesets.rst', $rst)." octets written for rulesets\n";
        
        $rst = file_get_contents('./docs/src/Rules.rst');
        $replacement = preg_replace('/\$([0-9])/', '\\\\$0', $this->rules);
        $rst = preg_replace('/.. comment: Rules details(.*)d4a634700b94af15c6612b44000d8e148260503b/is',".. comment: Rules details\n.. comment: Generation date : $date\n.. comment: Generation hash : $hash\n\n$replacement", $rst);
        print file_put_contents('./docs/Rules.rst',$rst)." octets written for Rules\n";
    }
    
    private function finishGlossary() {
        $glossaryRst = <<<GLOSSARY
.. Glossary:

Glossary
============

GLOSSARY;
        ksort($this->glossary);
        
        $found = count($this->glossary, COUNT_RECURSIVE);
        print "$found entry in glossary found\n";

        print count($this->entries)." defined\n";
        
        foreach($this->entries as $name => $url) {
            $letter = strtoupper(trim($name,'\\`'))[0];
        }
        
        foreach($this->glossary as $letter => $items) {
            $glossaryRst .= "+ `$letter`\n";
            ksort($items);
            foreach($items as $key => $urls) {
                ksort($urls);
                $glossaryRst .= "    + `".stripslashes($key)."`\n
      + ".implode("\n      + ",array_keys($urls))."\n\n";
            }
            $glossaryRst .= "\n";
        }
        $glossaryRst .= "\n";

        print file_put_contents('docs/Glossary.rst',$glossaryRst)." octets written for Rules\n";
    }
    
    private function prepareParameterList() {
        ksort($this->parameter_list);
        
        $parameterList = array();
        foreach($this->parameter_list as $analyzer => $parameters) {
            $label = str_replace(' ', '-', strtolower($analyzer));
            $analyzerList = "+ :ref:`$label`\n";
            ksort($parameters);
            foreach($parameters as $name => $default) {
                $analyzerList .= "   + $name : $default\n";
            }
            $parameterList[] = $analyzerList;
        }

        $this->parameter_list = $parameterList;
    }
    
    private function readPHPversion(string $phpversion) : string {
        if ($phpversion[-1] === '-') {
            return 'With PHP '.substr($phpversion, 0, -1).' and older';
        } elseif ($phpversion[-1] === '+') {
            return 'With PHP '.substr($phpversion, 0, -1).' and more recent';
        } else {
            return $phpversion;
        }
    }
    
    private function prepareDevelopment() {
        $files = glob('docs/src/Atoms/*.json');
        
        foreach($files as $file) {
            if (strpos( $file, '.auto.') !== false) { continue; }
            $name = basename($file, '.json');
            $atoms[$name] = array_merge(json_decode(file_get_contents($file), \JSON_OBJECT_AS_ARRAY),
                                        json_decode(file_get_contents(str_replace('.json', '.auto.json', $file) ?: '[]'), \JSON_OBJECT_AS_ARRAY)
                                        );
        }
        
        $this->list_atoms = array();
        $this->details_atoms = array();
        foreach($atoms as $name => $atom) {
            $properties = array_diff( array_keys($atom), ['in', 'out', 'name', 'url', 'description', 'token']);
            sort($properties);
            if (empty($atom['token'])) {
                $atom['token'] = array();
            } else {
                $atom['token'] = (array) $atom['token'];
                sort($atom['token']);
            }
            if (empty($atom['in'])) {
                $atom['in'] = array();
            } else {
                $atom['in'] = (array) $atom['in'];
                ksort($atom['in']);
            }
            if (empty($atom['out'])) {
                $atom['out'] = array();
            } else {
                $atom['out'] = (array) $atom['out'];
                ksort($atom['out']);
            }
            
            if (!isset($atom['name'])) {
                print_r($atom);
                die('Docs eror with '.$name);
            }

            $this->list_atoms[] = "* {$atom['name']} : {$atom['description']}";

            $this->details_atoms[] = "{$atom['name']}\n___________________________\n\n".PHP_EOL.
                                     "{$atom['description']}\n".PHP_EOL.
                                     ".. image:: images/$atom[name].png
                            :alt: $atom[name]'s outgoing diagramm".PHP_EOL.PHP_EOL.
                                     "List of available properties : \n\n* ".implode("\n* ", $properties).PHP_EOL.PHP_EOL.
                                     "List of possible tokens : \n\n* ".implode("\n* ", $atom['token']).PHP_EOL.PHP_EOL.
                                     "List of outgoing links : \n\n* ".implode("\n* ", array_keys($atom['out'])).PHP_EOL.PHP_EOL.
                                     "List of incoming links : \n\n* ".implode("\n* ", array_keys($atom['in'])).PHP_EOL.PHP_EOL;

            if (file_exists("docs/src/Atoms/$atom[name].png")) {
                copy("docs/src/Atoms/$atom[name].png", "docs/images/$atom[name].png" );
            }
        }

        $this->list_atoms = 'Here is the list of the '.count($atoms).' available atoms : '
                            .PHP_EOL.PHP_EOL.
                            implode(PHP_EOL, $this->list_atoms);

        $this->details_atoms = PHP_EOL.implode(PHP_EOL, $this->details_atoms);


        $files = glob('human/en/DSL/*.ini');
        foreach($files as $file) {
            $ini = parse_ini_file($file);
            
            $this->list_steps[] = "* $ini[title] : $ini[description]\n";
        }
        
        $this->list_steps = 'Here is the list of the '.count($this->list_steps).' available steps : '
                            .PHP_EOL.PHP_EOL.
                            implode(PHP_EOL, $this->list_steps);

    }
    
    
    private function prepareExakatExtensions() {
        $json = file_get_contents('https://www.exakat.io/extensions/index.json');
        $list = json_decode($json);
        
        $list2 = array();
        foreach($list as $ext) {
            $list2[$ext->name] = $ext;
        }
        ksort($list2);
        $list = $list2;
        unset($list2);
        
        $this->exakat_extension_list = 'List of extensions : there are '.count($list).' extensions'.PHP_EOL.PHP_EOL;
        $this->exakat_extension_det  = 'Details about the extensions'.PHP_EOL.
                                        str_repeat('-', 28).
                                        PHP_EOL.PHP_EOL;

        $summary = array();
        $details = array();
        foreach($list as $ext) {
            $summary[] = '* '.$this->rst_link($ext->name, $this->rst_anchor('extension '.$ext->name)); 
            
            $details[] = $this->makeExtensionDoc($ext);
        }

        $this->exakat_extension_list .= implode(PHP_EOL, $summary).PHP_EOL.PHP_EOL.PHP_EOL;
        $this->exakat_extension_det  .= implode(PHP_EOL, $details).PHP_EOL.PHP_EOL;
        
        print count($list)." extensions documented\n";
    }
    
    private function makeExtensionDoc($ext) {
        $doc = $this->rst_anchor_def('extension '.$ext->name).
               $ext->name.PHP_EOL.
               str_repeat('#', strlen($ext->name)).PHP_EOL.
               PHP_EOL;
               
        if (!file_exists("../Extensions/$ext->name")) { 
            $doc .= PHP_EOL.PHP_EOL;
            return $doc;
        }
        
        if (file_exists("../Extensions/{$ext->name}/human/en/docs.ini")) {
            $docs = parse_ini_file("../Extensions/{$ext->name}/human/en/docs.ini");
            $doc .= $docs['description']."\n";
            
            if (isset($docs['home_page'])) {
                $doc .= "\n";
                $doc .= "* **Home page** : `$docs[home_page] <$docs[home_page]>`_\n";
                if (!empty($docs['extension_page'])) {
                    $doc .= "* **Extension page** : `$docs[extension_page] <$docs[extension_page]>`_\n";
                }
                if (!empty($docs['versions'])) {
                    $versions = preg_replace('/^.*?(\d+\.\d+)(.*)$/', '$1 ($0)', $docs['versions']);
                    $doc .= "* **Supported versions** : ".implode(', ', $versions)."\n";
                }
                $doc .= "\n";
            }
        } else {
            $doc .= "This is extension $ext->name.\n\n";
        }

        $analyzers = parse_ini_file("../Extensions/{$ext->name}/Analyzer/analyzers.ini");
        if (empty($analyzers)) {
            $analyzers = array();
        }
        $doc .= "{$ext->name} analysis".PHP_EOL;
        $doc .= str_repeat('_', 50).PHP_EOL.PHP_EOL;
        
        $doc .= 'This extension includes '.count($analyzers[$ext->name]).' analyzers.'.PHP_EOL.PHP_EOL;
        $list = array();
        foreach($analyzers[$ext->name] as $analyzer) {
            $ini = parse_ini_file('../Extensions/'.$ext->name."/human/en/$analyzer.ini", true);
            $list[strtolower($ini['name'])] = $ini['name'].' ('.$analyzer.')';
            // Will be more detailled than that. Description, example...
        }
        ksort($list);
        $doc .= '* '.implode("\n* ", $list).PHP_EOL.PHP_EOL.PHP_EOL;
        
        // Include other rulesets than All and $ext->name
        $doc .= "{$ext->name} rulesets".PHP_EOL;
        $doc .= str_repeat('_', 50).PHP_EOL.PHP_EOL;

        $rulesets = parse_ini_file("../Extensions/$ext->name/Analyzer/analyzers.ini", true);
        unset($rulesets['All']);

        if (empty($rulesets)) {
            $doc .= 'This extension includes no specific ruleset.'.PHP_EOL;
        } elseif (count($rulesets) == 1) {
            $doc .= 'This extension includes one ruleset : '.array_keys($rulesets)[0].'.'.PHP_EOL.PHP_EOL.PHP_EOL;
        } elseif (count($rulesets) > 1) {
            $doc .= 'This extension includes '.count($rulesets).' rulesets.'.PHP_EOL.PHP_EOL;
            foreach($rulesets as $ruleset => $list) {
                $doc .= "* ".$ruleset.PHP_EOL;
            }
            $doc .= PHP_EOL;
        } // else : no report, no docs.

        // Reports
        // Include a presentation of the report : sections, usage. Must be in human/en/Report folder.
        $reports = glob("../Extensions/$ext->name/Reports/*.php");

        $doc .= "{$ext->name} reports".PHP_EOL;
        $doc .= str_repeat('_', 50).PHP_EOL.PHP_EOL;

        if (empty($reports)) {
            $doc .= 'This extension includes no specific report. Use generic reports, like Text to access the results.'.PHP_EOL;
        } elseif (count($reports) == 1) {
            $doc .= 'This extension includes one report : '.basename($reports[0], '.php').'.'.PHP_EOL;
        } elseif (count($reports) > 1) {
            $doc .= 'This extension includes '.count($reports).' reports.'.PHP_EOL.PHP_EOL.PHP_EOL;
            foreach($reports as $report) {
                $doc .= "* ".basename($report, '.php').PHP_EOL;
            }
            $doc .= PHP_EOL;
        } 

        $doc .= PHP_EOL.PHP_EOL;
        
        return $doc;
    }
    
    private function internalLink($text) {
        return preg_replace_callback('# ([^/ `\\\']+?/[^/ :`\\\']+?)( |\.)#s', function($m) {
            if (file_exists("./human/en/{$m[1]}.ini")) {
                $ini = parse_ini_file("./human/en/{$m[1]}.ini", true);

                return " :ref:`".$this->rst_anchor($ini['name'])."`$m[2] ";
            } else {
                return $m[0];
            }
        }, $text);
    }
}

?>