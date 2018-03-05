<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Docs;
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Helpers\Results;
use Exakat\Reports\Reports;

class Ambassador extends Reports {
    const FILE_FILENAME  = 'report';
    const FILE_EXTENSION = '';

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    protected $tmpName           = '';

    private $frequences        = array();
    private $timesToFix        = array();
    private $themesForAnalyzer = array();
    private $severities        = array();

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $inventories = array('constants'      => 'Constants',
                                 'classes'        => 'Classes',
                                 'interfaces'     => 'Interfaces',
                                 'functions'      => 'Functions',
                                 'traits'         => 'Traits',
                                 'namespaces'     => 'Namespaces',
                                 'Type/Url'       => 'URL',
                                 'Type/Regex'     => 'Regular Expr.',
                                 'Type/Sql'       => 'SQL',
                                 'Type/Email'     => 'Email',
                                 'Type/GPCIndex'  => 'Incoming variables',
                                 'Type/Md5string' => 'MD5 string',
                                 'Type/Mime'      => 'Mime types',
                                 );

    private $compatibilities = array('53' => 'Compatibility PHP 5.3',
                                     '54' => 'Compatibility PHP 5.4',
                                     '55' => 'Compatibility PHP 5.5',
                                     '56' => 'Compatibility PHP 5.6',
                                     '70' => 'Compatibility PHP 7.0',
                                     '71' => 'Compatibility PHP 7.1',
                                     '72' => 'Compatibility PHP 7.2',
                                     '73' => 'Compatibility PHP 7.3',
                                     );

    public function __construct($config) {
        parent::__construct($config);
        $this->frequences        = $this->themes->getFrequences();
        $this->timesToFix        = $this->themes->getTimesToFix();
        $this->themesForAnalyzer = $this->themes->getThemesForAnalyzer();
        $this->severities        = $this->themes->getSeverities();
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project_name);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = file_get_contents($this->tmpName.'/datas/menu.html');
            $inventories = '';
            foreach($this->inventories as $fileName => $title) {
                if (strpos($fileName, '/') !== false) {
                    $query = "SELECT sum(count) FROM resultsCounts WHERE analyzer == '$fileName' AND count > 0";
                    $total = $this->sqlite->querySingle($query);
                    if ($total < 1) {
                        continue;
                    }
                    $fileName = strtolower(basename($fileName));
                }
                $inventories .= "              <li><a href=\"inventories_$fileName.html\"><i class=\"fa fa-circle-o\"></i>$title</a></li>\n";
            }
            $compatibilities = '';
            $res = $this->sqlite->query('SELECT SUBSTR(key, -2) FROM hash WHERE key LIKE "Compatibility%"');
            while($row = $res->fetchArray(\SQLITE3_NUM)) {
                $compatibilities .= "              <li><a href=\"compatibility_php$row[0].html\"><i class=\"fa fa-circle-o\"></i>{$this->compatibilities[$row[0]]}</a></li>\n";
            }
            $menu = $this->injectBloc($menu, 'INVENTORIES', $inventories);
            $menu = $this->injectBloc($menu, 'COMPATIBILITIES', $compatibilities);
            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
        }

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    protected function putBasedPage($file, $html) {
        if (strpos($html, '{{BLOC-JS}}') !== false) {
            $html = str_replace('{{BLOC-JS}}', '', $html);
        }
        $html = str_replace('{{TITLE}}', 'PHP Static analysis for '.$this->config->project, $html);

        file_put_contents($this->tmpName.'/datas/'.$file.'.html', $html);
    }

    protected function injectBloc($html, $bloc, $content) {
        return str_replace("{{".$bloc."}}", $content, $html);
    }

    public function generate($folder, $name = self::FILE_FILENAME) {
        if ($name === self::STDOUT) {
            print "Can't produce Ambassador format to stdout\n";
            return false;
        }

        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateSettings();
        $this->generateProcFiles();

        $this->generateDashboard();
        $this->generateExtensionsBreakdown();
        $this->generateFiles();
        $this->generateAnalyzers();

        $this->generateIssues();
        $this->generateNoIssues();
        $this->generatePerformances();
        $this->generateSuggestions();
        $this->generateSecurity();
        
        $this->generateAnalyzersList();
        $this->generateExternalLib();

        $this->generateAppinfo();
//        $this->generateFileDependencies();
        $this->generateBugFixes();
        $this->generatePhpConfiguration();
        $this->generateExternalServices();
        $this->generateDirectiveList();
        $this->generateAlteredDirectives();
        $this->generateStats();
        $this->generateComplexExpressions();
        $this->generateVisibilitySuggestions();
        $this->generateMethodSize();
        
        $this->generateConfusingVariables();

        // Compatibility
        $this->generateCompilations();
        $res = $this->sqlite->query('SELECT SUBSTR(key, -2) FROM hash WHERE key LIKE "Compatibility%"');
        $list = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $list[] = 'CompatibilityPHP'.$row[0];
            $this->generateCompatibility($row[0]);
        }
        $this->generateIssuesEngine('compatibility_issues',
                                    $this->getIssuesFaceted($list));

        // Favorites
        $this->generateFavorites();

        // inventories
        $this->generateErrorMessages();
        $this->generateDynamicCode();
        $this->generateGlobals();
        $this->generateInventories();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateDocumentation();
        $this->generateCodes();

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    protected function initFolder() {
        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root.'/media/devfaceted', $this->tmpName );
    }

    protected function cleanFolder() {
        if (file_exists($this->tmpName.'/datas/base.html')) {
            unlink($this->tmpName.'/datas/base.html');
            unlink($this->tmpName.'/datas/menu.html');
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    private function getLinesFromFile($filePath,$lineNumber,$numberBeforeAndAfter){
        --$lineNumber; // array index
        $lines = array();
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath)) {

            $fileLines = file($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath);

            $startLine = 0;
            $endLine = 10;
            if(count($fileLines) > $lineNumber) {
                $startLine = $lineNumber-$numberBeforeAndAfter;
                if($startLine<0)
                    $startLine=0;

                if($lineNumber+$numberBeforeAndAfter < count($fileLines)-1 ) {
                    $endLine = $lineNumber+$numberBeforeAndAfter;
                } else {
                    $endLine = count($fileLines)-1;
                }
            }

            for ($i=$startLine; $i < $endLine+1 ; ++$i) {
                $lines[]= array(
                            'line' => $i + 1,
                            'code' => $fileLines[$i]
                    );
            }
        }
        return $lines;
    }

    private function php2html($description){
        $return = str_replace('<br />', '', $description[0]);
        $return = PHPSyntax($return);
        
        return '</p><pre>'
                .$return
                .'</pre><p>';
    }

    protected function setPHPBlocs($description){
        $description = nl2br($description);

        $description = preg_replace("#`(.*?) <(.*?)>`_#is", '<a href="$2" title="$1">$1 <i class="fa fa-sign-out"></i></a>', $description);
        
        $description = preg_replace_callback("#<\?php(.*?)\?>#is", array($this, 'php2html'), $description);
        

        return $description;
    }

    private function generateDocumentation(){
        $datas = array();
        $baseHTML = $this->getBasedPage('analyzers_doc');
        $analyzersDocHTML = "";

        $analyzersList = array_merge($this->themes->getThemeAnalyzers($this->themesToShow),
                                     $this->themes->getThemeAnalyzers('Preferences'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP53'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP54'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP55'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP56'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP70'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP71'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP72'),
                                     $this->themes->getThemeAnalyzers('CompatibilityPHP73')
                                     );
        $analyzersList = array_keys(array_count_values($analyzersList));
                                     
        foreach($analyzersList as $analyzerName) {
            $analyzer = $this->themes->getInstance($analyzerName, null, $this->config);
            $description = $analyzer->getDescription();

            $analyzersDocHTML.='<h2><a href="analyzers_doc.html#analyzer='.$analyzerName.'" id="'.$this->toId($analyzerName).'">'.$description->getName().' <i class="fa fa-search" style="font-size: 14px"></i></a></h2>';

            $badges = array();
            $v = $description->getVersionAdded();
            if(!empty($v)){
                $badges[] = '[Since '.$v.']';
            }
            $badges[] = '[ -P '.$analyzer->getInBaseName().' ]';

            $versionCompatibility = $analyzer->getPhpversion();
            if ($versionCompatibility !== Analyzer::PHP_VERSION_ANY) {
                if (strpos($versionCompatibility, '+') !== false) {
                    $versionCompatibility = substr($versionCompatibility, 0, -1).' and more recent ';
                } elseif (strpos($versionCompatibility, '-') !== false) {
                    $versionCompatibility = ' older than '.substr($versionCompatibility, 0, -1);
                }
                $badges[] = '[ PHP '.$versionCompatibility.']';
            }

            $analyzersDocHTML .= '<p>'.implode(' - ', $badges).'</p>';
            $analyzersDocHTML .= '<p>'.$this->setPHPBlocs($description->getDescription()).'</p>';

            $v = $description->getClearPHP();
            if(!empty($v)){
                $analyzersDocHTML.='<p>This rule is named <a target="_blank" href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$description->getClearPHP().'.md">'.$description->getClearPHP().'</a>, in the clearPHP reference.</p>';
            }
        }
        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyzersDocHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/highlight.pack.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Analyzers\' documentation');

        $this->putBasedPage('analyzers_doc', $finalHTML);
    }

    private function generateSecurity() {
        $this->generateIssuesEngine('security_issues',
                                    $this->getIssuesFaceted('Security') );
    }

    private function generateSuggestions() {
        $this->generateIssuesEngine('suggestions',
                                    $this->getIssuesFaceted('Suggestions') );
    }

    private function generatePerformances() {
        $this->generateIssuesEngine('performances_issues',
                                    $this->getIssuesFaceted('Performances') );
    }

    private function generateFavorites() {
        $baseHTML = $this->getBasedPage('favorites_dashboard');

        $analyzers = $this->themes->getThemeAnalyzers('Preferences');
        
        $donut = array();
        $html = array(' ');

        foreach($analyzers as $analyzer) {
            $list = $this->datastore->getHashAnalyzer($analyzer);

            $table = '';
            $values = array();
            $object = $this->themes->getInstance($analyzer, null, $this->config);
            $name = $object->getDescription()->getName();

            $total = 0;
            foreach($list as $key => $value) {
                $table .= '
                <div class="clearfix">
                   <div class="block-cell">'.makeHtml($key).'</div>
                   <div class="block-cell text-center">'.$value.'</div>
                 </div>
';
                if ($value > 0) {
                    $values[] = '{label:"'.$key.'", value:'.( (int) $value).'}';
                }
                $total += $value;
            }
            $nb = 4 - count($list);
            for($i = 0; $i < $nb; ++$i) {
                $table .= '
                <div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>
';
            }
            // Ignore if we have no occurrences
            if ($total === 0) { continue; }
            $values = implode(', ', $values);

            $html[] = <<<HTML
            <div class="col-md-3">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><a href="favorites_issues.html#analyzer=$analyzer" title="$name">$name</a></h3>
                </div>
                <div class="box-body chart-responsive">
                  <div id="donut-chart_$name"></div>
                  <div class="clearfix">
                    <div class="block-cell bold">Number</div>
                    <div class="block-cell bold text-center">Count</div>
                  </div>
                  $table
                </div>
                <!-- /.box-body -->
              </div>
            </div>
HTML;
            if (count($html) % 5 === 0) {
                $html[] = '          </div>
          <div class="row">';
            }
            $donut[] = <<<JAVASCRIPT
      Morris.Donut({
        element: 'donut-chart_$name',
        resize: true,
        colors: ["#3c8dbc", "#f56954", "#00a65a", "#1424b8"],
        data: [$values]
      });

JAVASCRIPT;
        }
        $donut = implode(PHP_EOL, $donut);

        $donut = <<<JAVASCRIPT
  <script>
    $(document).ready(function() {
      $donut
      Highcharts.theme = {
         colors: ["#F56954", "#f7a35c", "#ffea6f", "#D2D6DE"],
         chart: {
            backgroundColor: null,
            style: {
               fontFamily: "Dosis, sans-serif"
            }
         },
         title: {
            style: {
               fontSize: '16px',
               fontWeight: 'bold',
               textTransform: 'uppercase'
            }
         },
         tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
         },
         legend: {
            itemStyle: {
               fontWeight: 'bold',
               fontSize: '13px'
            }
         },
         xAxis: {
            gridLineWidth: 1,
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         yAxis: {
            minorTickInterval: 'auto',
            title: {
               style: {
                  textTransform: 'uppercase'
               }
            },
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         plotOptions: {
            candlestick: {
               lineColor: '#404048'
            }
         },


         // General
         background2: '#F0F0EA'
      };

      // Apply the theme
      Highcharts.setOptions(Highcharts.theme);

      $('#filename').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAFILES]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Critical',
              data: [SCRIPTDATACRITICAL]
          }, {
              name: 'Major',
              data: [SCRIPTDATAMAJOR]
          }, {
              name: 'Minor',
              data: [SCRIPTDATAMINOR]
          }, {
              name: 'None',
              data: [SCRIPTDATANONE]
          }]
      });

      $('#container').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAANALYZERLIST]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Critical',
              data: [SCRIPTDATAANALYZERCRITICAL]
          }, {
              name: 'Major',
              data: [SCRIPTDATAANALYZERMAJOR]
          }, {
              name: 'Minor',
              data: [SCRIPTDATAANALYZERMINOR]
          }, {
              name: 'None',
              data: [SCRIPTDATAANALYZERNONE]
          }]
      });
    });
  </script>

JAVASCRIPT;
        $html = '<div class="row">'.implode(PHP_EOL, $html).'</div>';

        $baseHTML = $this->injectBloc($baseHTML, 'FAVORITES', $html);
        $baseHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $donut);
        $baseHTML = $this->injectBloc($baseHTML, 'TITLE', 'Favorites\' dashboard');
        $this->putBasedPage('favorites_dashboard', $baseHTML);

        $baseHTML = $this->getBasedPage('favorites_issues');

        $this->generateIssuesEngine('favorites_issues',
                                    $this->getIssuesFaceted('Preferences') );
    }

    protected function generateDashboard() {
        $baseHTML = $this->getBasedPage('index');

        $tags = array();
        $code = array();

        // Bloc top left
        $hashData = $this->getHashData();
        $finalHTML = $this->injectBloc($baseHTML, 'BLOCHASHDATA', $hashData);

        // bloc Issues
        $issues = $this->getIssuesBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCISSUES', $issues['html']);
        $tags[] = 'SCRIPTISSUES';
        $code[] = $issues['script'];

        // bloc severity
        $severity = $this->getSeverityBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCSEVERITY', $severity['html']);
        $tags[] = 'SCRIPTSEVERITY';
        $code[] = $severity['script'];

        // Marking the audit date
        $this->makeAuditDate($finalHTML);

        // top 10
        $fileHTML = $this->getTopFile();
        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers();
        $finalHTML = $this->injectBloc($finalHTML, 'TOPANALYZER', $analyzerHTML);

        $blocjs = <<<JAVASCRIPT
  <script>
    $(document).ready(function() {
      Morris.Donut({
        element: 'donut-chart_issues',
        resize: true,
        colors: ["#3c8dbc", "#f56954", "#00a65a", "#1424b8"],
        data: [SCRIPTISSUES]
      });
      Morris.Donut({
        element: 'donut-chart_severity',
        resize: true,
        colors: ["#3c8dbc", "#f56954", "#00a65a", "#1424b8"],
        data: [SCRIPTSEVERITY]
      });
      Highcharts.theme = {
         colors: ["#F56954", "#f7a35c", "#ffea6f", "#D2D6DE"],
         chart: {
            backgroundColor: null,
            style: {
               fontFamily: "Dosis, sans-serif"
            }
         },
         title: {
            style: {
               fontSize: '16px',
               fontWeight: 'bold',
               textTransform: 'uppercase'
            }
         },
         tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
         },
         legend: {
            itemStyle: {
               fontWeight: 'bold',
               fontSize: '13px'
            }
         },
         xAxis: {
            gridLineWidth: 1,
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         yAxis: {
            minorTickInterval: 'auto',
            title: {
               style: {
                  textTransform: 'uppercase'
               }
            },
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         plotOptions: {
            candlestick: {
               lineColor: '#404048'
            }
         },


         // General
         background2: '#F0F0EA'
      };

      // Apply the theme
      Highcharts.setOptions(Highcharts.theme);

      $('#filename').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAFILES]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Critical',
              data: [SCRIPTDATACRITICAL]
          }, {
              name: 'Major',
              data: [SCRIPTDATAMAJOR]
          }, {
              name: 'Minor',
              data: [SCRIPTDATAMINOR]
          }, {
              name: 'None',
              data: [SCRIPTDATANONE]
          }]
      });

      $('#container').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAANALYZERLIST]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Critical',
              data: [SCRIPTDATAANALYZERCRITICAL]
          }, {
              name: 'Major',
              data: [SCRIPTDATAANALYZERMAJOR]
          }, {
              name: 'Minor',
              data: [SCRIPTDATAANALYZERMINOR]
          }, {
              name: 'None',
              data: [SCRIPTDATAANALYZERNONE]
          }]
      });
    });
  </script>
JAVASCRIPT;

        // Filename Overview
        $fileOverview = $this->getFileOverview();
        $tags[] = 'SCRIPTDATAFILES';
        $code[] = $fileOverview['scriptDataFiles'];
        $tags[] = 'SCRIPTDATAMAJOR';
        $code[] = $fileOverview['scriptDataMajor'];
        $tags[] = 'SCRIPTDATACRITICAL';
        $code[] = $fileOverview['scriptDataCritical'];
        $tags[] = 'SCRIPTDATANONE';
        $code[] = $fileOverview['scriptDataNone'];
        $tags[] = 'SCRIPTDATAMINOR';
        $code[] = $fileOverview['scriptDataMinor'];

        // Analyzer Overview
        $analyzerOverview = $this->getAnalyzerOverview();
        $tags[] = 'SCRIPTDATAANALYZERLIST';
        $code[] = $analyzerOverview['scriptDataAnalyzer'];
        $tags[] = 'SCRIPTDATAANALYZERMAJOR';
        $code[] = $analyzerOverview['scriptDataAnalyzerMajor'];
        $tags[] = 'SCRIPTDATAANALYZERCRITICAL';
        $code[] = $analyzerOverview['scriptDataAnalyzerCritical'];
        $tags[] = 'SCRIPTDATAANALYZERNONE';
        $code[] = $analyzerOverview['scriptDataAnalyzerNone'];
        $tags[] = 'SCRIPTDATAANALYZERMINOR';
        $code[] = $analyzerOverview['scriptDataAnalyzerMinor'];

        $blocjs = str_replace($tags, $code, $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Issues\' dashboard');
        $this->putBasedPage('index', $finalHTML);
    }

    protected function generateExtensionsBreakdown() {
        $finalHTML = $this->getBasedPage('extension_list');

        // List of extensions used
        $res = $this->sqlite->query(<<<SQL
SELECT analyzer, count(*) AS count FROM results 
WHERE analyzer LIKE "Extensions/Ext%"
GROUP BY analyzer
ORDER BY count(*) DESC
SQL
        );
        $html = '';
        $xAxis = array();
        $data = array();
        while ($value = $res->fetchArray(\SQLITE3_ASSOC)) {
            $shortName = str_replace('Extensions/Ext', 'ext/', $value['analyzer']);
            $xAxis[] = "'".$shortName."'";
            $data[$value['analyzer']] = $value['count'];
            //                    <a href="#" title="' . $value['analyzer'] . '">
            $html .= '<div class="clearfix">
                      <div class="block-cell-name">'.$shortName.'</div>
                      <div class="block-cell-issue text-center">'.$value['count'].'</div>
                  </div>';
        }

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $blocjs = <<<JAVASCRIPT
  <script>
    $(document).ready(function() {
      Highcharts.theme = {
         colors: ["#F56954", "#f7a35c", "#ffea6f", "#D2D6DE"],
         chart: {
            backgroundColor: null,
            style: {
               fontFamily: "Dosis, sans-serif"
            }
         },
         title: {
            style: {
               fontSize: '16px',
               fontWeight: 'bold',
               textTransform: 'uppercase'
            }
         },
         tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
         },
         legend: {
            itemStyle: {
               fontWeight: 'bold',
               fontSize: '13px'
            }
         },
         xAxis: {
            gridLineWidth: 1,
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         yAxis: {
            minorTickInterval: 'auto',
            title: {
               style: {
                  textTransform: 'uppercase'
               }
            },
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         plotOptions: {
            candlestick: {
               lineColor: '#404048'
            }
         },


         // General
         background2: '#F0F0EA'
      };

      // Apply the theme
      Highcharts.setOptions(Highcharts.theme);

      $('#filename').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAFILES]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Calls',
              data: [CALLCOUNT]
          }]
      });

    });
  </script>
JAVASCRIPT;

        $tags = array();
        $code = array();

        // Filename Overview
        $tags[] = 'CALLCOUNT';
        $code[] = implode(', ', $data);
        $tags[] = 'SCRIPTDATAFILES';
        $code[] = implode(', ', $xAxis);

        $blocjs = str_replace($tags, $code, $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Extensions\' list');

        $this->putBasedPage('extension_list', $finalHTML);
    }

    public function getHashData() {
        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});

        $info = array(
            'Number of PHP files'                   => $this->datastore->getHash('files'),
            'Number of lines of code'               => $this->datastore->getHash('loc'),
            'Number of lines of code with comments' => $this->datastore->getHash('locTotal'),
            'PHP used'                              => $this->datastore->getHash('php_version'),
        );

        // fichier
        $totalFile = $this->datastore->getHash('files');
        $totalFileAnalysed = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFileAnalysed - $totalFile;
        if ($totalFile === 0) {
            $percentFile = 100;
        } else {
            $percentFile = abs(round($totalFileSansError / $totalFile * 100));
        }

        // analyzer
        list($totalAnalyzerUsed, $totalAnalyzerReporting) = $this->getTotalAnalyzer();
        $totalAnalyzerWithoutError = $totalAnalyzerUsed - $totalAnalyzerReporting;
        $percentAnalyzer = abs(round($totalAnalyzerWithoutError / $totalAnalyzerUsed * 100));
        
        $audit_date = date('r', strtotime('now'));

        $html = '<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Project Overview</h3>
                    </div>

                    <div class="box-body chart-responsive">
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of PHP</span> files</p>
                                <p class="value">'.$info['Number of PHP files'].'</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">'.$info['PHP used'].'</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> LoC</p>
                                <p class="value">'.$info['Number of lines of code'].'</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>Total</span> LoC</p>
                                <p class="value">'.$info['Number of lines of code with comments'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Files free of issues (%)</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentFile.'%">
                                        '.$totalFileSansError.'
                                    </div><div style="color:black; text-align:center;">'.$totalFileAnalysed.'</div>
                                </div>
                                <div class="pourcentage">'.$percentFile.'%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Analyzers free of issues (%)</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentAnalyzer.'%">
                                        '.$totalAnalyzerWithoutError.'
                                    </div><div style="color:black; text-align:center;">'.$totalAnalyzerReporting.'</div>
                                </div>
                                <div class="pourcentage">'.$percentAnalyzer.'%</div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    public function getIssuesBreakdown() {
        $receipt = array('Code Smells'  => 'Analyze',
                         'Dead Code'    => 'Dead code',
                         'Security'     => 'Security',
                         'Performances' => 'Performances');

        $data = array();
        foreach ($receipt AS $key => $categorie) {
            $list = 'IN ('.makeList($this->themes->getThemeAnalyzers($categorie)).')';
            $query = "SELECT sum(count) FROM resultsCounts WHERE analyzer $list AND count > 0";
            $total = $this->sqlite->querySingle($query);

            $data[] = array('label' => $key, 'value' => (int) $total);
        }

        // ordonné DESC par valeur
        uasort($data, function ($a, $b) {
            if ($a['value'] > $b['value']) {
                return -1;
            } elseif ($a['value'] < $b['value']) {
                return 1;
            } else {
                return 0;
            }
        });
        $issuesHtml = '';
        $dataScript = array();

        foreach ($data as $key => $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">'.$value['label'].'</div>
                   <div class="block-cell text-center">'.$value['value'].'</div>
                 </div>';
            $dataScript[] = '{label: "'.$value['label'].'", value: '.( (int) $value['value']).'}';
        }
        $dataScript = implode(', ', $dataScript);
        
        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html'   => $issuesHtml,
                     'script' => $dataScript);
    }

    public function getSeverityBreakdown() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = <<<SQL
                SELECT severity, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY severity
                    ORDER BY number DESC
SQL;
        $result = $this->sqlite->query($query);

        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('label' => $row['severity'],
                            'value' => $row['number']);
        }

        $html = '';
        $dataScript = array();
        foreach ($data as $key => $value) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">'.$value['label'].'</div>
                   <div class="block-cell text-center">'.$value['value'].'</div>
                 </div>';
            $dataScript[] = '{label: "'.$value['label'].'", value: '.( (int) $value['value']).'}';
        }
        $dataScript = implode(', ', $dataScript);

        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html' => $html, 'script' => $dataScript);
    }

    private function getTotalAnalysedFile() {
        $query = "SELECT COUNT(DISTINCT file) FROM results";
        $result = $this->sqlite->query($query);

        $result = $result->fetchArray(\SQLITE3_NUM);
        return $result[0];
    }

    private function getTotalAnalyzer($issues = false) {
        $query = "SELECT count(*) AS total, COUNT(CASE WHEN rc.count != 0 THEN 1 ELSE null END) AS yielding 
            FROM resultsCounts AS rc
            WHERE rc.count >= 0";

        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray(\SQLITE3_NUM);
    }

    private function generateAnalyzers() {
        $analysers = $this->getAnalyzersResultsCounts();

        $baseHTML = $this->getBasedPage('analyzers');
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML.= "<tr>";
            $analyserHTML.='<td>'.$analyser['label'].'</td>
                        <td>'.$analyser['recipes'].'</td>
                        <td>'.$analyser['issues'].'</td>
                        <td>'.$analyser['files'].'</td>
                        <td>'.$analyser['severity'].'</td>
                        <td>'.$this->frequences[$analyser['analyzer']].' %</td>';
            $analyserHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');

        $this->putBasedPage('analyzers', $finalHTML);
    }

    protected function getAnalyzersResultsCounts() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS issues, count(distinct file) AS files, severity AS severity 
        FROM results
        WHERE analyzer IN ($list)
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );

        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);
            $row['label'] = $analyzer->getDescription()->getName();
            $row['recipes' ] =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);

            $return[] = $row;
        }

        return $return;
    }

    private function getCountFileByAnalyzers($analyzer) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT file FROM results WHERE analyzer = :analyzer)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, \SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }
    
    private function generateNoIssues() {
        $list = array_merge($this->themes->getThemeAnalyzers('Analysis'),
                            $this->themes->getThemeAnalyzers('Security'),
                            $this->themes->getThemeAnalyzers('Performances'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP53'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP54'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP55'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP56'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP70'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP71'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP72'),
                            $this->themes->getThemeAnalyzers('CompatibilityPHP73'),
                            array('Project/Dump')
                            );
        $list = makeList($list);

        $query = <<<SQL
SELECT analyzer AS analyzer FROM resultsCounts
WHERE analyzer NOT IN ($list) AND 
      count = 0
SQL;
        $result = $this->sqlite->query($query);

        $baseHTML = $this->getBasedPage('no_issues');

        $filesHTML = '';

        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);
            
            if ($analyzer === null) {
                continue;
            }

            $filesHTML.= '<tr>';
            $filesHTML.= "<td><a href=\"analyzers_doc.html#analyzer=$row[analyzer]\" id=\"{$this->toId($row['analyzer'])}\"><i class=\"fa fa-book\" style=\"font-size: 14px\"></i></a>
                         &nbsp; {$analyzer->getDescription()->getName()}</td>";
            $filesHTML.= '</tr>';
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'No Issues Analysis');

        $this->putBasedPage('no_issues', $finalHTML);
    }

    private function generateFiles() {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage('files');
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= "<tr>";
            $filesHTML.='<td>'.$file['file'].'</td>
                        <td>'.$file['loc'].'</td>
                        <td>'.$file['issues'].'</td>
                        <td>'.$file['analyzers'].'</td>';
            $filesHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Files\' list');

        $this->putBasedPage('files', $finalHTML);
    }

    private function getFilesResultsCounts() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $result = $this->sqlite->query(<<<SQL
SELECT file AS file, line AS loc, count(*) AS issues, count(distinct analyzer) AS analyzers FROM results
        GROUP BY file
SQL
        );
        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['file']] = $row;
        }

        return $return;
    }

    private function getCountAnalyzersByFile($file) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT analyzer FROM results WHERE file = :file)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':file', $file, \SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }

    public function getFilesCount($limit = null) {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = "SELECT file, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY file
                    ORDER BY number DESC ";
        if ($limit !== null) {
            $query .= " LIMIT ".$limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('file'  => $row['file'],
                            'value' => $row['number']);
        }

        return $data;
    }

    protected function getTopFile() {
        $data = $this->getFilesCount(self::TOPLIMIT);

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="issues.html#file='.$value['file'].'" title="'.$value['file'].'">
                      <div class="block-cell-name">'.$value['file'].'</div>
                    </a>
                    <div class="block-cell-issue text-center">'.$value['value'].'</div>
                  </div>';
        }
        $nb = 10 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                      <div class="block-cell-name">&nbsp;</div>
                      <div class="block-cell-issue text-center">&nbsp;</div>
                  </div>';
        }

        return $html;
    }

    protected function getFileOverview() {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();
        $severities = $this->getSeveritiesNumberBy('file');
        foreach ($data as $value) {
            $xAxis[] = "'".$value['file']."'";
            $dataCritical[] = empty($severities[$value['file']]['Critical']) ? 0 : $severities[$value['file']]['Critical'];
            $dataMajor[]    = empty($severities[$value['file']]['Major'])    ? 0 : $severities[$value['file']]['Major'];
            $dataMinor[]    = empty($severities[$value['file']]['Minor'])    ? 0 : $severities[$value['file']]['Minor'];
            $dataNone[]     = empty($severities[$value['file']]['None'])     ? 0 : $severities[$value['file']]['None'];
        }
        $xAxis        = implode(', ', $xAxis);
        $dataCritical = implode(', ', $dataCritical);
        $dataMajor    = implode(', ', $dataMajor);
        $dataMinor    = implode(', ', $dataMinor);
        $dataNone     = implode(', ', $dataNone);

        return array(
            'scriptDataFiles'    => $xAxis,
            'scriptDataMajor'    => $dataMajor,
            'scriptDataCritical' => $dataCritical,
            'scriptDataNone'     => $dataNone,
            'scriptDataMinor'    => $dataMinor
        );
    }

    private function getAnalyzersCount($limit) {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC ";
        if ($limit) {
            $query .= " LIMIT ".$limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('analyzer' => $row['analyzer'],
                            'value'    => $row['number']);
        }

        return $data;
    }

    protected function getTopAnalyzers() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC
                    LIMIT ".self::TOPLIMIT;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);
            $data[] = array('label' => $analyzer->getDescription()->getName(),
                            'value' => $row['number'],
                            'name'  => $row['analyzer']);
        }

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="issues.html#analyzer='.$value['name'].'" title="'.$value['label'].'">
                      <div class="block-cell-name">'.$value['label'].'</div> 
                    </a>
                    <div class="block-cell-issue text-center">'.$value['value'].'</div>
                  </div>';
        }

        return $html;
    }

    private function getSeveritiesNumberBy($type = 'file') {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY $type, severity
SQL;

        $stmt = $this->sqlite->query($query);

        $return = array();
        while ($row = $stmt->fetchArray(\SQLITE3_ASSOC) ) {
            if ( isset($return[$row[$type]]) ) {
                $return[$row[$type]][$row['severity']] = $row['count'];
            } else {
                $return[$row[$type]] = array($row['severity'] => $row['count']);
            }
        }

        return $return;
    }

    protected function getAnalyzerOverview() {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $xAxis[] = "'".$value['analyzer']."'";
            $dataCritical[] = empty($severities[$value['analyzer']]['Critical']) ? 0 : $severities[$value['analyzer']]['Critical'];
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major'])    ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor'])    ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None'])     ? 0 : $severities[$value['analyzer']]['None'];
        }
        $xAxis = implode(', ', $xAxis);
        $dataCritical = implode(', ', $dataCritical);
        $dataMajor = implode(', ', $dataMajor);
        $dataMinor = implode(', ', $dataMinor);
        $dataNone = implode(', ', $dataNone);

        return array(
            'scriptDataAnalyzer'         => $xAxis,
            'scriptDataAnalyzerMajor'    => $dataMajor,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerNone'     => $dataNone,
            'scriptDataAnalyzerMinor'    => $dataMinor
        );
    }

    private function generateIssues() {
        $this->generateIssuesEngine('issues',
                                    $this->getIssuesFaceted($this->themesToShow) );
    }
    
    protected function generateIssuesEngine($filename, $issues) {
        $baseHTML = $this->getBasedPage($filename, $issues);

        $total = count($issues);
        $issues = implode(', '.PHP_EOL, $issues);
        $blocjs = <<<JAVASCRIPTCODE
        
  <script src="facetedsearch.js"></script>
  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [
$issues
];

      var item_template =  
        '<tr>' +
          '<td width="20%"><a href="<%= "analyzers_doc.html#" + obj.analyzer_md5 %>" title="Documentation for <%= obj.analyzer %>"><i class="fa fa-book"></i></a> <%= obj.analyzer %></td>' +
          '<td width="20%"><a href="<%= "codes.html#file=" + obj.file + "&line=" + obj.line %>" title="Go to code"><%= obj.file + ":" + obj.line %></a></td>' +
          '<td width="18%"><%= obj.code %></td>' + 
          '<td width="2%"><%= obj.code_detail %></td>' +
          '<td width="7%" align="center"><%= obj.severity %></td>' +
          '<td width="7%" align="center"><%= obj.complexity %></td>' +
          '<td width="16%"><%= obj.recipe %></td>' +
        '</tr>' +
        '<tr class="fullcode">' +
          '<td colspan="7" width="100%"><div class="analyzer_help"><%= obj.analyzer_help %></div><pre><code><%= obj.code_plus %></code><div class="text-right"><a target="_BLANK" href="codes.html?file=<%= obj.link_file %>" class="btn btn-info">View File</a></div></pre></td>' +
        '</tr>';
      var settings = { 
        items           : data_items,
        facets          : { 
          'analyzer'  : 'Analyzer',
          'file'      : 'File',
          'severity'  : 'Severity',
          'complexity': 'Complexity',
          'receipt'   : 'Receipt'
        },
        facetContainer     : '<div class="facetsearch btn-group" id=<%= id %> ></div>',
        facetTitleTemplate : '<button class="facettitle multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected"><span class="multiselect-selected-text"><%= title %></span><b class="caret"></b></button>',
        facetListContainer : '<ul class="facetlist multiselect-container dropdown-menu"></ul>',
        listItemTemplate   : '<li class=facetitem id="<%= id %>" data-analyzer="<%= data_analyzer %>" data-file="<%= data_file %>"><span class="check"></span><%= name %><span class=facetitemcount>(<%= count %>)</span></li>',
        bottomContainer    : '<div class=bottomline></div>',  
        resultSelector   : '#results',
        facetSelector    : '#facets',
        resultTemplate   : item_template,
        paginationCount  : 50
      }   
      $.facetelize(settings);
      
      var analyzerParam = window.location.hash.split('analyzer=')[1];
      var fileParam = window.location.hash.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam.toLowerCase() + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam.toLowerCase() + "']").click();
      }
    });
  </script>
JAVASCRIPTCODE;

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Issues\' list');
        $finalHTML = $this->injectBloc($finalHTML, 'TOTAL', $total);
        $this->putBasedPage($filename, $finalHTML);
    }

    public function getIssuesFaceted($theme) {
        $list = $this->themes->getThemeAnalyzers($theme);
        $list = makeList($list);

        $sqlQuery = <<<SQL
            SELECT fullcode, file, line, analyzer
                FROM results
                WHERE analyzer IN ($list)

SQL;
        $result = $this->sqlite->query($sqlQuery);

        $TTFColors = array('Instant'  => '#5f492d',
                           'Quick'    => '#e8d568',
                           'Slow'     => '#d06960',
                           'None'     => '#89070b'
                           );

        $severityColors = array('Critical' => '#ff0000',   // red 
                                'Major'    => '#FFA500',   // Orange
                                'Minor'    => '#BDB76B',   // darkkhaki
                                'None'     => '#D3D3D3',   // lightgrey
                                );

        $items = array();
        while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $item = array();
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $item['analyzer']     =  $ini['name'];
            $item['analyzer_md5'] = $this->toId($row['analyzer']);
            $item['file' ]        =  $row['file'];
            $item['file_md5' ]    =  $this->toId($row['file']);
            $item['code' ]        = PHPSyntax($row['fullcode']);
            $item['code_detail']  = "<i class=\"fa fa-plus \"></i>";
            $item['code_plus']    = PHPSyntax($row['fullcode']);
            $item['link_file']    = $row['file'];
            $item['line' ]        =  $row['line'];
            $item['severity']     = "<i class=\"fa fa-warning\" style=\"color: ".$severityColors[$this->severities[$row['analyzer']]]."\"></i>";
            $item['complexity']   = "<i class=\"fa fa-cog\" style=\"color: ".$TTFColors[$this->timesToFix[$row['analyzer']]]."\"></i>";
            $item['recipe' ]      =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);
            $lines                = explode("\n", $ini['description']);
            $item['analyzer_help' ] = $lines[0];

            $items[] = json_encode($item);
            $this->count();
        }

        return $items;
    }

    private function getClassByType($type)
    {
        if ($type == 'Critical' || $type == 'Long') {
            $class = 'text-orange';
        } elseif ($type == 'Major' || $type == 'Slow') {
            $class = 'text-red';
        } elseif ($type == 'Minor' || $type == 'Quick') {
            $class = 'text-yellow';
        }  elseif ($type == 'Note' || $type == 'Instant') {
            $class = 'text-blue';
        } else {
            $class = 'text-gray';
        }

        return $class;
    }

    private function generateSettings() {
        $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        if (!empty($this->config->project_url)) {
            $info[] = array('Home page', '<a href="'.$this->config->project_url.'">'.$this->config->project_url.'</a>');
        }
        $vcs_type = $this->datastore->gethash('vcs_type');
        if ($vcs_type === 'git') {
            $info[] = array('Git URL', $this->datastore->gethash('vcs_url'));

            $res = $this->datastore->gethash('vcs_branch');
            if (!empty($res)) { 
                $info[] = array('Git branch', trim($res));
            }

            $res = $this->datastore->gethash('vcs_revision');
            if (!empty($res)) { 
                $info[] = array('Git commit', trim($res));
            }
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $info[] = array('Number of PHP files', $this->datastore->getHash('files'));
        $info[] = array('Number of lines of code', $this->datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $this->datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));

        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        $settings = '';
        foreach($info as $i) {
            $settings .= "<tr><td>$i[0]</td><td>$i[1]</td></tr>";
        }

        $html = $this->getBasedPage('used_settings');
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        $html = $this->injectBloc($html, 'TITLE', 'Analyzer settings\' list');

        $this->putBasedPage('used_settings', $html);
    }

    private function generateProcFiles() {
        $files = '';
        $fileList = $this->datastore->getCol('files', 'file');
        foreach($fileList as $file) {
            $files .= "<tr><td>$file</td></tr>\n";
        }

        $nonFiles = '';
        $ignoredFiles = $this->datastore->getRow('ignoredFiles');
        foreach($ignoredFiles as $row) {
            if (empty($row['file'])) { continue; }

            $nonFiles .= "<tr><td>{$row['file']}</td><td>{$row['reason']}</td></tr>\n";
        }

        $html = $this->getBasedPage('proc_files');
        $html = $this->injectBloc($html, 'FILES', $files);
        $html = $this->injectBloc($html, 'NON-FILES', $nonFiles);
        $html = $this->injectBloc($html, 'TITLE', 'Processed Files\' list');

        $this->putBasedPage('proc_files', $html);
    }

    private function generateAnalyzersList() {
        $analyzers = '';

        foreach($this->themes->getThemeAnalyzers($this->themesToShow) as $analyzer) {
            $analyzer = $this->themes->getInstance($analyzer, null, $this->config);
            $description = $analyzer->getDescription();

            $analyzers .= "<tr><td>".$description->getName()."</td></tr>\n";
        }

        $html = $this->getBasedPage('proc_analyzers');
        $html = $this->injectBloc($html, 'ANALYZERS', $analyzers);
        $html = $this->injectBloc($html, 'TITLE', 'Processed Analyzers\' list');

        $this->putBasedPage('proc_analyzers', $html);
    }

    private function generateExternalLib() {
        $externallibraries = json_decode(file_get_contents($this->config->dir_root.'/data/externallibraries.json'));

        $libraries = '';
        $externallibrariesList = $this->datastore->getRow('externallibraries');

        foreach($externallibrariesList as $row) {
            $url = $externallibraries->{strtolower($row['library'])}->homepage;
            $name = $externallibraries->{strtolower($row['library'])}->name;
            if (empty($url)) {
                $homepage = '';
            } else {
                $homepage = "<a href=\"".$url."\">".$row['library']."</a>";
            }
            $libraries .= "<tr><td>$name</td><td>$row[file]</td><td>$homepage</td></tr>\n";
        }

        $html = $this->getBasedPage('ext_lib');
        $html = $this->injectBloc($html, 'LIBRARIES', $libraries);
        $html = $this->injectBloc($html, 'TITLE', 'External Libraries\' list');

        $this->putBasedPage('ext_lib', $html);
    }

    protected function generateBugFixes() {
        $table = '';

        $data = new Methods($this->config);
        $bugfixes = $data->getBugFixes();

        $results = new Results($this->sqlite, 'Php/MiddleVersion');
        $results->load();

        $rows = array();
        foreach($results->toArray() as $row) {
            $rows[strtolower(substr($row['fullcode'], 0, strpos($row['fullcode'], '(')))] = $row;
        }

        foreach($bugfixes as $bugfix) {
            if (!empty($bugfix['function'])) {
                if (!isset($rows[$bugfix['function']])) { continue; }

                $cve = $this->Bugfixes_cve($bugfix['cve']);
                $table .= '<tr>
    <td>'.$bugfix['title'].'</td>
    <td>'.($bugfix['solvedIn72']  ? $bugfix['solvedIn72']  : '-').'</td>
    <td>'.($bugfix['solvedIn71']  ? $bugfix['solvedIn71']  : '-').'</td>
    <td>'.($bugfix['solvedIn70']  ? $bugfix['solvedIn70']  : '-').'</td>
    <td>'.($bugfix['solvedIn56']  ? $bugfix['solvedIn56']  : '-').'</td>
    <td>'.($bugfix['solvedIn55']  ? $bugfix['solvedIn55']  : '-').'</td>
    <td>'.($bugfix['solvedInDev']  ? $bugfix['solvedInDev']  : '-').'</td>
    <td><a href="https://bugs.php.net/bug.php?id='.$bugfix['bugs'].'">#'.$bugfix['bugs'].'</a></td>
    <td>'.$cve.'</td>
                </tr>';
            } elseif (!empty($bugfix['analyzer'])) {
                $subanalyze = $this->sqlite->querySingle('SELECT count FROM resultsCounts WHERE analyzer = "'.$bugfix['analyzer'].'"');

                $cve = $this->Bugfixes_cve($bugfix['cve']);

                if ($subanalyze == 0) { continue; }
                $table .= '<tr>
    <td>'.$bugfix['title'].'</td>
    <td>'.($bugfix['solvedIn72']  ? $bugfix['solvedIn72']  : '-').'</td>
    <td>'.($bugfix['solvedIn71']  ? $bugfix['solvedIn71']  : '-').'</td>
    <td>'.($bugfix['solvedIn70']  ? $bugfix['solvedIn70']  : '-').'</td>
    <td>'.($bugfix['solvedIn56']  ? $bugfix['solvedIn56']  : '-').'</td>
    <td>'.($bugfix['solvedIn55']  ? $bugfix['solvedIn55']  : '-').'</td>
    <td>'.($bugfix['solvedInDev']  ? $bugfix['solvedInDev']  : '-').'</td>
    <td><a href="https://bugs.php.net/bug.php?id='.$bugfix['bugs'].'">#'.$bugfix['bugs'].'</a></td>
    <td>'.$cve.'</td>
                </tr>';
            } else {
                continue; // ignore. Possibly some mis-configuration
            }
        }

        $html = $this->getBasedPage('bugfixes');
        $html = $this->injectBloc($html, 'BUG_FIXES', $table);
        $this->putBasedPage('bugfixes', $html);
    }

    protected function generatePhpConfiguration() {
        $phpConfiguration = new PhpCompilation($this->config);
        $report = $phpConfiguration->generate(null, Reports::INLINE);

        $id = strpos($report, "\n\n\n");
        $configline = trim($report);
        $configline = str_replace(array(' ', "\n") , array("&nbsp;", "<br />\n",), $configline);
        
        $html = $this->getBasedPage('php_compilation');
        $html = $this->injectBloc($html, 'COMPILATION', $configline);
        $html = $this->injectBloc($html, 'TITLE', 'PHP Configurations\' list');

        $this->putBasedPage('php_compilation', $html);
    }

    protected function generateAnalyzerSettings() {
        $settings = '';

        $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        if (!empty($this->config->project_url)) {
            $info[] = array('Home page', '<a href="'.$this->config->project_url.'">'.$this->config->project_url.'</a>');
        }
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $info[] = array('Git URL', $r[1]);

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git branch');
            $info[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git rev-parse HEAD');
            $info[] = array('Git commit', trim($res));
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $info[] = array('Number of PHP files', $this->datastore->getHash('files'));
        $info[] = array('Number of lines of code', $this->datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $this->datastore->getHash('locTotal'));

        $info[] = array('Analysis execution date', date('r', $this->datastore->getHash('audit_end')));
        $info[] = array('Analysis runtime', duration($this->datastore->getHash('audit_end') - $this->datastore->getHash('audit_start')));
        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $this->config->phpversion.' ('.$php->getActualVersion().')');

        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        foreach($info as &$row) {
            $row = '<tr><td>'.implode('</td><td>', $row).'</td></tr>';
        }
        unset($row);

        $settings = implode('', $info);

        $html = $this->getBasedPage('annex_settings');
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        $this->putBasedPage('annex_settings', $html);
    }

    private function generateErrorMessages() {
        $errorMessages = '';

        $results = new Results($this->sqlite, 'Structures/ErrorMessages');
        $results->load();
        
        foreach($results->toArray() as $row) {
            $errorMessages .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
        }

        $html = $this->getBasedPage('error_messages');
        $html = $this->injectBloc($html, 'ERROR_MESSAGES', $errorMessages);
        $this->putBasedPage('error_messages', $html);
    }

    private function generateExternalServices() {
        $externalServices = '';

        $res = $this->datastore->getRow('configFiles');
        foreach($res as $row) {
            if (empty($row['homepage'])) {
                $link = '';
            } else {
                $link = "<a href=\"".$row['homepage']."\">".$row['homepage']."&nbsp;<i class=\"fa fa-sign-out\"></i></a>";
            }

            $externalServices .= "<tr><td>$row[name]</td><td>$row[file]</td><td>$link</td></tr>\n";
        }

        $html = $this->getBasedPage('external_services');
        $html = $this->injectBloc($html, 'EXTERNAL_SERVICES', $externalServices);
        $this->putBasedPage('external_services', $html);
    }

    private function generateDirectiveList() {
        // @todo automate this : Each string must be found in Report/Content/Directives/*.php and vice-versa
        $directives = array('standard', 'bcmath', 'date', 'file',
                            'fileupload', 'mail', 'ob', 'env',
                            // standard extensions
                            'apc', 'amqp', 'apache', 'assertion', 'curl', 'dba',
                            'filter', 'image', 'intl', 'ldap',
                            'mbstring',
                            'opcache', 'openssl', 'pcre', 'pdo', 'pgsql',
                            'session', 'sqlite', 'sqlite3',
                            // pecl extensions
                            'com', 'eaccelerator',
                            'geoip', 'ibase',
                            'imagick', 'mailparse', 'mongo',
                            'trader', 'wincache', 'xcache'
                             );

        $directiveList = '';
        $res = $this->sqlite->query(<<<SQL
SELECT analyzer FROM resultsCounts 
    WHERE ( analyzer LIKE "Extensions/Ext%" OR 
            analyzer IN ("Structures/FileUploadUsage", 
                         "Php/UsesEnv",
                         "Php/UseBrowscap"))
        AND count > 0
SQL
        );
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($row['analyzer'] == 'Structures/FileUploadUsage') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>File Upload</td></tr>\n";
                $data['File Upload'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/fileupload.json'));
            } elseif ($row['analyzer'] == 'Php/UsesEnv') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Environnement</td></tr>\n";
                $data['Environnement'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/env.json'));
            } elseif ($row['analyzer'] == 'Php/UseBrowscap') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Browser</td></tr>\n";
                $data['Environnement'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/browscap.json'));
            } elseif ($row['analyzer'] == 'Php/ErrorLogUsage') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Error Log</td></tr>\n";
                $data['Errorlog'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/errorlog.json'));
            } else {
                $ext = substr($row['analyzer'], 14);
                if (in_array($ext, $directives)) {
                    $data = json_decode(file_get_contents($this->config->dir_root.'/data/directives/'.$ext.'.json'));
                    $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>$ext</td></tr>\n";
                    foreach($data as $row) {
                        $directiveList .= "<tr><td>$row->name</td><td>$row->suggested</td><td>$row->documentation</td></tr>\n";
                    }
                }
            }
        }

        $html = $this->getBasedPage('directive_list');
        $html = $this->injectBloc($html, 'DIRECTIVE_LIST', $directiveList);
        $this->putBasedPage('directive_list', $html);
    }

    private function generateCompilations() {
        $compilations = '';

        $total = $this->sqlite->querySingle('SELECT value FROM hash WHERE key = "files"');
        $info = array();
        
        foreach(array_merge(array($this->config->phpversion[0].$this->config->phpversion[2]), $this->config->other_php_versions) as $suffix) {
            $version = $suffix[0].'.'.$suffix[1];
            $res = $this->sqlite->querySingle('SELECT name FROM sqlite_master WHERE type="table" AND name="compilation'.$suffix.'"');
            if (!$res) {
                $compilations .= "<tr><td>$version</td><td>N/A</td><td>N/A</td><td>Compilation not tested</td><td>N/A</td></tr>\n";
                continue; // Table was not created
            }

            $res = $this->sqlite->query('SELECT file FROM compilation'.$suffix);
            $files = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $files[] = $row['file'];
            }
            if (empty($files)) {
                $files       = 'No compilation error found.';
                $errors      = 'N/A';
                $total_error = 'N/A';
            } else {
                $res = $this->sqlite->query('SELECT error FROM compilation'.$suffix);
                $readErrors = array();
                while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                    $readErrors[] = $row['error'];
                }
                $errors      = array_count_values($readErrors);
                $errors      = array_keys($errors);
                $errors      = array_keys(array_count_values($errors));
                $errors       = '<ul><li>'.implode("</li>\n<li>", $errors).'</li></ul>';

                $total_error = count($files).' ('.number_format(count($files) / $total * 100, 0).'%)';
                $files       = array_keys(array_count_values($files));
                $files       = '<ul><li>'.implode("</li>\n<li>", $files).'</li></ul>';
            }

            $compilations .= "<tr><td>$version</td><td>$total</td><td>$total_error</td><td>$files</td><td>$errors</td></tr>\n";
        }

        $html = $this->getBasedPage('compatibility_compilations');
        $html = $this->injectBloc($html, 'COMPILATIONS', $compilations);
        $html = $this->injectBloc($html, 'TITLE', 'Compilations overview');
        $this->putBasedPage('compatibility_compilations', $html);
    }

    private function generateCompatibility($version) {
        $compatibility = '';

        $list = $this->themes->getThemeAnalyzers('CompatibilityPHP'.$version);

        $res = $this->sqlite->query('SELECT analyzer, counts FROM analyzed');
        $counts = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }

        foreach($list as $l) {
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$l.'.ini');
            if (isset($counts[$l])) {
                $result = (int) $counts[$l];
            } else {
                $result = -2; // -2 == not run
            }
            $result = $this->Compatibility($result, $l);
            $name = $ini['name'];
            $link = '<a href="analyzers_doc.html#'.$this->toId($l).'" alt="Documentation for $name"><i class="fa fa-book"></i></a>';
            $compatibility .= "<tr><td>$link $name</td><td>$result</td></tr>\n";
        }

        $description = <<<HTML
<i class="fa fa-check-square-o"></i> : Nothing found for this analysis, proceed with caution; <i class="fa fa-warning red"></i> : some issues found, check this; <i class="fa fa-ban"></i> : Can't test this, PHP version incompatible; <i class="fa fa-cogs"></i> : Can't test this, PHP configuration incompatible; 
HTML;

        $html = $this->getBasedPage('compatibility');
        $html = $this->injectBloc($html, 'COMPATIBILITY', $compatibility);
        $html = $this->injectBloc($html, 'TITLE', 'Compatibility PHP '.$version[0].'.'.$version[1]);
        $html = $this->injectBloc($html, 'DESCRIPTION', $description);
        $this->putBasedPage('compatibility_php'.$version, $html);
    }

    private function generateDynamicCode() {
        $dynamicCode = '';

        $results = new Results($this->sqlite, 'Structures/DynamicCode');
        $results->load();

        foreach($results->toArray() as $row) {
            $dynamicCode .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
        }

        $html = $this->getBasedPage('dynamic_code');
        $html = $this->injectBloc($html, 'DYNAMIC_CODE', $dynamicCode);
        $this->putBasedPage('dynamic_code', $html);
    }

    private function generateGlobals() {
        $theGlobals = '';

        $results = new Results($this->sqlite, 'Structures/GlobalInGlobal');
        $results->load();

        foreach($results->toArray() as $row) {
            $theGlobals .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
        }

        $html = $this->getBasedPage('globals');
        $html = $this->injectBloc($html, 'GLOBALS', $theGlobals);
        $this->putBasedPage('globals', $html);
    }

    private function generateInventories() {
        $definitions = array(
            'constants'  => array('description' => 'List of all defined constants in the code.',
                                  'analyzer'    => 'Constants/Constantnames'),
            'classes'    => array('description' => 'List of all defined classes in the code.',
                                  'analyzer'    => 'Classes/Classnames'),
            'interfaces' => array('description' => 'List of all defined interfaces in the code.',
                                  'analyzer'    => 'Interfaces/Interfacenames'),
            'traits'     => array('description' => 'List of all defined traits in the code.',
                                  'analyzer'    => 'Traits/Traitnames'),
            'functions'  => array('description' => 'List of all defined functions in the code.',
                                  'analyzer'    => 'Functions/Functionnames'),
            'namespaces' => array('description' => 'List of all defined namespaces in the code.',
                                  'analyzer'    => 'Namespaces/Namespacesnames'),
            'Type/Url'   => array('description' => 'List of all URL mentionned in the code.',
                                  'analyzer'    => 'Type/Url'),
            'Type/Regex' => array('description' => 'List of all PCRE regular expressions mentionned in the code.',
                                  'analyzer'    => 'Type/Regex'),
            'Type/Sql'   => array('description' => 'List of all SQL queries mentionned in the code.',
                                  'analyzer'    => 'Type/Sql'),
            'Type/Email' => array('description' => 'List of all Email mentionned in the code.',
                                  'analyzer'    => 'Type/Email'),
            'Type/GPCIndex' => array('description' => 'List of all incoming variables mentionned in the code.',
                                  'analyzer'    => 'Type/GPCIndex'),
            'Type/Md5string' => array('description' => 'List of all incoming MD5-like strings mentionned in the code.',
                                  'analyzer'    => 'Type/GPCIndex'),
            'Type/Mime' => array('description' => 'List of all Mime-type mentionned in the code.',
                                  'analyzer'    => 'Type/GPCIndex'),
//            'exceptions' => array('description' => 'List of all defined exceptions.',
//                                  'analyzer'    => 'Exceptions/DefinedExceptions'),
        );
        foreach($this->inventories as $fileName => $theTitle) {
            $theDescription = $definitions[$fileName]['description'];
            $theAnalyzer    = $definitions[$fileName]['analyzer'];
            
            if (strpos($fileName, '/') !== false) {
                $fileName = strtolower(basename($fileName));
            }

            $theTable = '';

            $results = new Results($this->sqlite, $theAnalyzer);
            $results->load();

           foreach($results->toArray() as $row) {
                $theTable .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
            }

            $html = $this->getBasedPage('inventories');
            $html = $this->injectBloc($html, 'TITLE', $theTitle);
            $html = $this->injectBloc($html, 'DESCRIPTION', $theDescription);
            $html = $this->injectBloc($html, 'TABLE', $theTable);
            $this->putBasedPage('inventories_'.$fileName, $html);
        }
        $this->generateExceptionTree();
        $this->generateNamespaceTree();
    }

    private function generateExceptionTree() {
        $exceptions = array (
  'Throwable' => 
  array (
    'Error' => 
    array (
      'ParseError' => 
      array (
      ),
      'TypeError' => 
      array (
        'ArgumentCountError' => 
        array (
        ),
      ),
      'ArithmeticError' => 
      array (
        'DivisionByZeroError' => 
        array (
        ),
      ),
      'AssertionError' => 
      array (
      ),
    ),
    'Exception' => 
    array (
      'ErrorException' => 
      array (
      ),
      'ClosedGeneratorException' => 
      array (
      ),
      'DOMException' => 
      array (
      ),
      'LogicException' => 
      array (
        'BadFunctionCallException' => 
        array (
          'BadMethodCallException' => 
          array (
          ),
        ),
        'DomainException' => 
        array (
        ),
        'InvalidArgumentException' => 
        array (
        ),
        'LengthException' => 
        array (
        ),
        'OutOfRangeException' => 
        array (
        ),
      ),
      'RuntimeException' => 
      array (
        'OutOfBoundsException' => 
        array (
        ),
        'OverflowException' => 
        array (
        ),
        'RangeException' => 
        array (
        ),
        'UnderflowException' => 
        array (
        ),
        'UnexpectedValueException' => 
        array (
        ),
        'PDOException' => 
        array (
        ),
      ),
      'PharException' => 
      array (
      ),
      'ReflectionException' => 
      array (
      ),
    ),
  ),
);
        $list = array();

        $theTable = '';
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Exceptions/DefinedExceptions"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!preg_match('/ extends (\S+)/', $row['fullcode'], $r)) {
                continue;
            }
            $parent = strtolower($r[1]);
            if ($parent[0] != '\\') {
                $parent = '\\'.$parent;
            }

            if (!isset($list[$parent])) {
                $list[$parent] = array();
            } 
            
            $list[$parent][] = $row['fullcode'];
        }
        
        foreach($list as &$l) {
            sort($l);
        }
        $theTable = $this->tree2ul($exceptions, $list);

        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'TITLE', 'Exceptions inventory');
        $html = $this->injectBloc($html, 'DESCRIPTION', '');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage('inventories_exceptions', $html);
    }
    
    private function path2tree($paths) {
        $return = array();
        
        $recursive = array();
        foreach($paths as $path) {
            $folders = explode('\\', $path);
            
            $first = empty($folders[0]) ? '\\' : $folders[0]; 
            
            if (!isset($return[$first])) {
                $return[$first] = array();
            }
            
            if (count($folders) > 2) {
                $recursive[$first] = 1;
            }
            $return[$first][] = implode('\\', array_slice($folders, 1));
        }
        
        foreach($recursive as $recurrent => $foo) {
            $return[$recurrent] = $this->path2tree($return[$recurrent]);
        }
        
        return $return;
    }
    
    private function pathtree2ul($path) {
        if (empty($path)) {
            return '';
        }
        $return = '<ul>';
        
        foreach($path as $k => $v) {
            $return .= '<li>';

            $parent = '\\'.strtolower($k);
            if (is_string($v)) {
                $return .= '<div style="font-weight: bold">'.$v.'</div>';
            } elseif (count($v) == 1) {
                if (empty($v[0])) {
                    if (empty($k)) {
                        $return .= '<div style="font-weight: bold">\\</div>';
                    } else {
                        $return .= '<div style="font-weight: bold">'.$k.'</div>';
                    }
                } else {
                    $return .= '<div style="font-weight: bold">'.$k.'</div>'.$this->pathtree2ul($v);
                }
            } else {
                $return .= '<div style="font-weight: bold">'.$k.'</div>'.$this->pathtree2ul($v);
            }

            $return .= '</li>';
        }
        
        $return .= '</ul>';
        
        return $return;
    }
    
    private function generateNamespaceTree() {
        $theTable = '';
        $res = $this->sqlite->query('SELECT namespace FROM namespaces WHERE namespace != "\app\console" ORDER BY namespace');
        
        $paths = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $paths[] = substr($row['namespace'], 1);
        }
        
        $paths = $this->path2tree($paths);
        $theTable = $this->pathtree2ul($paths);
        
        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'TITLE', 'Namespace tree');
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the various namespaces in use in the code.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage('inventories_namespaces', $html);
    }
    
    private function tree2ul($tree, $display) {
        if (empty($tree)) {
            return '';
        }
        $return = '<ul>';
        
        foreach($tree as $k => $v) {
            $return .= '<li>';

            $parent = '\\'.strtolower($k);
            if (isset($display[$parent])) {
                $return .= '<div style="font-weight: bold">'.$k.'</div><ul><li>'.implode('</li><li>', $display[$parent]).'</li></ul>';
            } else {
                $return .= '<div style="font-weight: bold; color: darkgray">'.$k.'</div>';
            }

            if (is_array($v)) {
                $return .= $this->tree2ul($v, $display);
            } 

            $return .= '</li>';
        }
        
        $return .= '</ul>';
        
        return $return;
    }

    private function generateVisibilitySuggestions() {
        $constants  = $this->generateVisibilityConstantSuggestions();
        $properties = $this->generateVisibilityPropertySuggestions();
        $methods    = $this->generateVisibilityMethodsSuggestions();
        
        $classes = array_unique(array_merge(array_keys($constants),
                                            array_keys($properties),
                                            array_keys($methods)));
        
        $visibilityTable = <<<HTML
<table class="table table-striped">
    <tr>
        <td>&nbsp;</td>
        <td>Name</td>
        <td>Value</td>
        <td>None (public)</td>
        <td>Public</td>
        <td>Protected</td>
        <td>Private</td>
        <td>Constant</td>
    </tr>
HTML;

        foreach($classes as $id) {
            list(, $class) = explode(':', $id);
            $visibilityTable .= '<tr><td colspan="9">class '.$class.'</td></tr>'.PHP_EOL.
                                (isset($constants[$id])  ? implode('', $constants[$id])  : '').
                                (isset($properties[$id]) ? implode('', $properties[$id]) : '').
                                (isset($methods[$id])    ? implode('', $methods[$id])    : '');
        }

        $visibilityTable .= '</table>';

        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'TITLE', 'Visibility recommendations');
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Below, is a summary of all classes and their component\'s visiblity. Whenever a visibility is set and used at the right level, a green star is presented. Whenever it is set to a level, but could be updated to another, red and orange stars are mentioned. ');
        $html = $this->injectBloc($html, 'CONTENT', $visibilityTable);
        $this->putBasedPage('visibility_suggestions', $html);
    }

    private function generateVisibilityMethodsSuggestions() {
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBePrivateMethod"');
        $couldBePrivate = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['class'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[2]);

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $row['fullcode'];
            } else {
                $couldBePrivate[$fullnspath] = array($row['fullcode']);
            }
        }

        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBeProtectedMethod"');
        $couldBeProtected = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['class'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[2]);
            
            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $row['fullcode'];
            } else {
                $couldBeProtected[$fullnspath] = array($row['fullcode']);
            }
        }
        
        $res = $this->sqlite->query('
        SELECT cit.name AS theClass, namespaces.namespace || "\\" || lower(cit.name) AS fullnspath,
         visibility, method
        FROM cit
        JOIN methods 
            ON methods.citId = cit.id
        JOIN namespaces 
            ON cit.namespaceId = namespaces.id
         WHERE type="class"
        ');
        $ranking = array(''          => 0,
                         'public'    => 1,
                         'protected' => 2,
                         'private'   => 3);

        $return = array();
        $theClass = '';
        $aClass = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($theClass != $row['fullnspath'].':'.$row['theClass']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'].':'.$row['theClass'];
                $aClass = array();
            }

            $visibilities = array('&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) && 
                in_array($row['method'], $couldBePrivate[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) && 
                in_array($row['method'], $couldBeProtected[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }

            $aClass[] = '<tr><td>&nbsp;</td><td>'.$row['method'].'</td><td>'.
                                    implode('</td><td>', $visibilities)
                                 .'</td></tr>'.PHP_EOL;
        }

        $return[$theClass] = $aClass;
        unset($return['']);
        
        return $return;
    }
    
    private function generateVisibilityConstantSuggestions() {
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBePrivateConstante"');
        $couldBePrivate = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!preg_match('/class (\S+) /i', $row['class'], $classname)) {
                continue; // it is an interface or a trait
            }

            $fullnspath = $row['namespace'].'\\'.strtolower($classname[1]);
            
            if (!preg_match('/^(.+) = /i', $row['fullcode'], $code)) {
                continue;
            }

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $code[1];
            } else {
                $couldBePrivate[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBeProtectedConstant"');
        $couldBeProtected = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!preg_match('/class (\S+) /i', $row['class'], $classname)) {
                continue; // it is an interface or a trait
            }
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[1]);
            
            if (!preg_match('/^(.+) = /i', $row['fullcode'], $code)) {
                continue;
            }
            
            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $code[1];
            } else {
                $couldBeProtected[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->sqlite->query('
        SELECT cit.name AS theClass, namespaces.namespace || "\\" || lower(cit.name) AS fullnspath,
         visibility, constant, value
        FROM cit
        JOIN constants 
            ON constants.citId = cit.id
        JOIN namespaces 
            ON cit.namespaceId = namespaces.id
         WHERE type="class"
        ');
        $theClass = '';
        $ranking = array(''          => 1,
                         'public'    => 2,
                         'protected' => 3,
                         'private'   => 4,
                         'constant'  => 5);
        $return = array();

        $aClass = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($theClass != $row['fullnspath'].':'.$row['theClass']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'].':'.$row['theClass'];
                $aClass = array();
            }

            $visibilities = array($row['value'], '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) && 
                in_array($row['constant'], $couldBePrivate[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) && 
                in_array($row['constant'], $couldBeProtected[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }
        
            $aClass[] = '<tr><td>&nbsp;</td><td>'.$row['constant'].'</td><td>'.
                                    implode('</td><td>', $visibilities)
                                 .'</td></tr>'.PHP_EOL;
        }

        $return[$theClass] = $aClass;
        unset($return['']);

        return $return;
    }

    private function generateVisibilityPropertySuggestions() {

        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBePrivate"');
        $couldBePrivate = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            assert(isset($classname[1]), 'Missing class in '.$row['class']);
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[2]);
            
            preg_match('/(\$\S+)/i', $row['fullcode'], $code);
            assert(isset($code[1]), 'Missing class in '.$row['fullcode']);

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $code[1];
            } else {
                $couldBePrivate[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBeProtectedProperty"');
        $couldBeProtected = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[1]);
            
            preg_match('/(\$\S+)/', $row['fullcode'], $code);
            
            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $code[1];
            } else {
                $couldBeProtected[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Classes/CouldBeClassConstant"');
        $couldBeConstant = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            $fullnspath = $row['namespace'].'\\'.strtolower($classname[1]);
            
            preg_match('/(\$\S+)/', $row['fullcode'], $code);
            
            if (isset($couldBeConstant[$fullnspath])) {
                $couldBeConstant[$fullnspath][] = $code[1];
            } else {
                $couldBeConstant[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->sqlite->query('SELECT cit.name AS theClass, namespaces.namespace || "\\" || lower(cit.name) AS fullnspath,
         visibility, property, value
        FROM cit
        JOIN properties 
            ON properties.citId = cit.id
        JOIN namespaces 
            ON cit.namespaceId = namespaces.id
         WHERE type="class"
        ');
        $theClass = '';
        $ranking = array(''          => 1,
                         'public'    => 2,
                         'protected' => 3,
                         'private'   => 4,
                         'constant'  => 5);
        $return = array();

        $aClass = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($theClass != $row['fullnspath'].':'.$row['theClass']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'].':'.$row['theClass'];
                $aClass = array();
            }

            $visibilities = array($row['value'], '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) && 
                in_array($row['property'], $couldBePrivate[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) && 
                in_array($row['property'], $couldBeProtected[$row['fullnspath']])) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }

            if (isset($couldBeConstant[$row['fullnspath']]) && 
                in_array($row['property'], $couldBeConstant[$row['fullnspath']])) {
                    $visibilities[$ranking['constant']] = '<i class="fa fa-star" style="color:black"></i>';
            }
            
            $aClass[] = '<tr><td>&nbsp;</td><td>'.$row['property'].'</td><td>'.
                            implode('</td><td>', $visibilities)
                            .'</td></tr>'.PHP_EOL;
        }
        $return[$theClass] = $aClass;
        unset($return['']);

        return $return;
    }
    
    private function generateAlteredDirectives() {
        $alteredDirectives = '';
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Php/DirectivesUsage"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $alteredDirectives .= '<tr><td>'.PHPSyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('altered_directives');
        $html = $this->injectBloc($html, 'ALTERED_DIRECTIVES', $alteredDirectives);
        $this->putBasedPage('altered_directives', $html);
    }

    private function generateMethodSize() {
        $finalHTML = $this->getBasedPage('cit_size');

        // List of extensions used
        $res = $this->sqlite->query(<<<SQL
SELECT namespaces.namespace || '\\' || name AS name, name AS shortName, files.file, (end - begin) AS size 
    FROM cit 
    JOIN files 
        ON files.id = cit.file
    JOIN namespaces 
        ON namespaces.id = cit.namespaceId
    ORDER BY (end - begin) DESC
SQL
        );
        $html = '';
        $xAxis = array();
        $data = array();
        while ($value = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (count($data) < 50) {
                $data[$value['name']] = $value['size'];
                $xAxis[] = "'".$value['shortName']."'";
            }
            $html .= '<div class="clearfix">
                      <div class="block-cell-name">'.$value['name'].'</div>
                      <div class="block-cell-issue text-center">'.$value['size'].'</div>
                  </div>';
        }

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $blocjs = <<<JAVASCRIPT
  <script>
    $(document).ready(function() {
      Highcharts.theme = {
         colors: ["#F56954", "#f7a35c", "#ffea6f", "#D2D6DE"],
         chart: {
            backgroundColor: null,
            style: {
               fontFamily: "Dosis, sans-serif"
            }
         },
         title: {
            style: {
               fontSize: '16px',
               fontWeight: 'bold',
               textTransform: 'uppercase'
            }
         },
         tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
         },
         legend: {
            itemStyle: {
               fontWeight: 'bold',
               fontSize: '13px'
            }
         },
         xAxis: {
            gridLineWidth: 1,
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         yAxis: {
            minorTickInterval: 'auto',
            title: {
               style: {
                  textTransform: 'uppercase'
               }
            },
            labels: {
               style: {
                  fontSize: '12px'
               }
            }
         },
         plotOptions: {
            candlestick: {
               lineColor: '#404048'
            }
         },


         // General
         background2: '#F0F0EA'
      };

      // Apply the theme
      Highcharts.setOptions(Highcharts.theme);

      $('#filename').highcharts({
          credits: {
            enabled: false
          },

          exporting: {
            enabled: false
          },

          chart: {
              type: 'column'
          },
          title: {
              text: ''
          },
          xAxis: {
              categories: [SCRIPTDATAFILES]
          },
          yAxis: {
              min: 0,
              title: {
                  text: ''
              },
              stackLabels: {
                  enabled: false,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                  }
              }
          },
          legend: {
              align: 'right',
              x: 0,
              verticalAlign: 'top',
              y: -10,
              floating: false,
              backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
              borderColor: '#CCC',
              borderWidth: 1,
              shadow: false
          },
          tooltip: {
              headerFormat: '<b>{point.x}</b><br/>',
              pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
          },
          plotOptions: {
              column: {
                  stacking: 'normal',
                  dataLabels: {
                      enabled: false,
                      color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                      style: {
                          textShadow: '0 0 3px black'
                      }
                  }
              }
          },
          series: [{
              name: 'Lines',
              data: [CALLCOUNT]
          }]
      });

    });
  </script>
JAVASCRIPT;

        $tags = array();
        $code = array();

        // Filename Overview
        $tags[] = 'CALLCOUNT';
        $code[] = implode(', ', $data);
        $tags[] = 'SCRIPTDATAFILES';
        $code[] = implode(', ', $xAxis);

        $blocjs = str_replace($tags, $code, $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Class, Interface and Trait size');

        $this->putBasedPage('cit_size', $finalHTML);
    }
    
    private function generateStats() {
        $results = new Stats($this->config);
        $report = $results->generate(null, Reports::INLINE);
        $report = json_decode($report);
        
        $stats = '';
        foreach($report as $section => $hash) {
            $stats .= "<tr><td colspan=2 bgcolor=\"#BBB\">$section</td></tr>\n";

            foreach($hash as $name => $count) {
                $stats .= "<tr><td>$name</td><td>$count</td></tr>\n";
            }
        }
        
        $html = $this->getBasedPage('stats');
        $html = $this->injectBloc($html, 'STATS', $stats);
        $this->putBasedPage('stats', $html);
    }

    private function generateComplexExpressions() {
        $results = new Results($this->sqlite, 'Structures/ComplexExpression');
        $results->load();
        
        $expr = $results->getColumn('fullcode');
        $counts = array_count_values($expr);

        $expressions = '';
        foreach($results->toArray() as $row) {
            $expressions .= "<tr><td>{$row['file']}:{$row['line']}</td><td>{$counts[$row['fullcode']]}</td><td>{$row['fullcode']}</td></tr>\n";
        }

        $html = $this->getBasedPage('complex_expressions');
        $html = $this->injectBloc($html, 'BLOC-EXPRESSIONS', $expressions);
        $this->putBasedPage('complex_expressions', $html);
    }

    private function generateCodes() {
        mkdir($this->tmpName.'/datas/sources/', 0755);

        $filesList = $this->datastore->getRow('files');
        $files = '';
        foreach($filesList as $row) {
            $id = str_replace('/', '_', $row['file']);

            $subdirs = explode('/', dirname($row['file']));
            $dir = $this->tmpName.'/datas/sources';
            foreach($subdirs as $subdir) {
                $dir .= '/'.$subdir;
                if (!file_exists($dir)) {
                    mkdir($dir, 0755);
                }
            }

            $path = dirname($this->tmpName).'/code/'.$row['file'];
            if (!file_exists($path)) {
                continue;
            }
            $source = @show_source($path, true);
            $files .= '<li><a href="#" id="'.$id.'" class="menuitem">'.makeHtml($row['file'])."</a></li>\n";
            $source = substr($source, 6, -8);
            $source = preg_replace_callback('#<br />#is', function($x) { static $i = 0; return '<br /><a name="l'.++$i.'" />'; }, $source);
            file_put_contents($this->tmpName.'/datas/sources/'.$row['file'], $source);
        }

        $blocjs = <<<JAVASCRIPT
  <script src="facetedsearch.js"></script>


  <script>
  "use strict";

  $('.menuitem').click(function(event){
    $('#results').load("sources/" + event.target.text);
    $('#filename').html(event.target.text + '  <span class="caret"></span>');
  });

  var fileParam = window.location.hash.split('file=')[1];
  if(fileParam !== undefined) {
    var limit = fileParam.indexOf('&');
    if (limit !== -1) {
        fileParam = fileParam.substr(0, limit);
    }
    $('#results').load("sources/" + fileParam);
    $('#filename').html(fileParam + '  <span class="caret"></span>');
  }

  var line = window.location.hash.split('line=')[1];
  if(line !== undefined) {
        window.location.hash = 'l' + line;
  }
  

  </script>
JAVASCRIPT;
        $html = $this->getBasedPage('codes');
        $html = $this->injectBloc($html, 'BLOC-JS', $blocjs);
        $html = $this->injectBloc($html, 'FILES', $files);

        $this->putBasedPage('codes', $html);
    }

    private function generateFileDependencies() {
        $res = $this->sqlite->query('SELECT * FROM filesDependencies WHERE included != including AND type in ("IMPLEMENTS", "EXTENDS", "INCLUDE", "NEW")');

        $nodes = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($nodes[$row['including']][$row['included']])) {
                $nodes[$row['including']][$row['included']] .= ', '.$row['type'];
            } else {
                $nodes[$row['including']][$row['included']] = $row['type'];
            }
        }
        
        $next = array();
        foreach($nodes as $in => $out) {
            $set = false;
            
            foreach($next as $file => &$inc) {
                if ($file === $in) {
                    $inc = $out;
                    $set = true;
                }
            }
            
            if ($set === false) {
                $next[$in] = $out;
            }
        }

        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'TITLE', 'File dependencies tree');
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Tree');
        $html = $this->injectBloc($html, 'CONTENT', 'content');
        $this->putBasedPage('files_tree', $html);
    }

    private function generateConfusingVariables() {

        $results = new Results($this->sqlite, 'Variables/CloseNaming');
        $results->load();
        $variables = array_unique($results->getColumn('fullcode'));
        
        $figures = range(0, 9);
        $results = array();
        foreach($variables as $id1 => $var1) {
            foreach($variables as $id2 => $var2) {
                if ($id1 >= $id2) { continue; }
                if (mb_strtolower($var1) === mb_strtolower($var2)) {
                    $results[] = [$var1, $var2, 'Different by case only'];
                    continue;
                }
                if (levenshtein($var1, $var2) === 1) {
                    $results[] = [$var1, $var2, 'Too few differences'];
                    continue;
                }
                if (str_replace('_', '', $var1) === str_replace('_', '', $var2)) {
                    $results[] = [$var1, $var2, 'Different by _ only'];
                    continue;
                }
                if (str_replace($figures, '', $var1) === str_replace($figures, '', $var2)) {
                    $results[] = [$var1, $var2, 'Different by figures only'];
                    continue;
                }
            }
        }
        
        $results = array_map(function($row) { 
            return "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
        }, $results);
        $results = join('', $results);

        $html = $this->getBasedPage('variables_confusing');
        $html = $this->injectBloc($html, 'CONTENT', $results);
        $this->putBasedPage('variables_confusing', $html);
    }
    
    private function generateAppinfo() {
        $data = new Data\Appinfo($this->sqlite);
        $data->prepare();
        
        $list = array();
        $originals = $data->originals();
        foreach($data->values() as $section => $points) {
            $listPoint = array();
            foreach($points as $point => $status) {
                
                if (isset($originals[$section][$point]) && isset($this->frequences[$originals[$section][$point]])) {
                    $percentage = $this->frequences[$originals[$section][$point]];
                    $percentageDisplay = $percentage;
                } else {
                    $percentage = 0;
                    $percentageDisplay = 'N/A';
                }
                
                $statusIcon = $this->makeIcon($status);
                $htmlPoint = makeHtml($point);
                $listPoint[] = <<<HTML
<li><div style="width: 90%; text-align: left;display: inline-block;">$statusIcon&nbsp;$htmlPoint&nbsp;</div><div style="display: inline-block; width: 10%;"><span class="progress progress-sm"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: $percentage%; color:black;">$percentageDisplay %</div><div>&nbsp;</div></span></li>
HTML;
            }

            $listPoint = implode(PHP_EOL, $listPoint);
            $list[] = <<<HTML
        <ul class="sidebar-menu">
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>$section</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                $listPoint
            </ul>
          </li>
        </ul>
HTML;
        }

        $list = implode("\n", $list);
        $list = <<<HTML
        <div class="sidebar">
$list
        </div>
HTML;

        $html = $this->getBasedPage('appinfo');
        $html = $this->injectBloc($html, 'APPINFO', $list);
        $this->putBasedPage('appinfo', $html);
    }

    protected function makeIcon($tag) {
        switch($tag) {
            case self::YES :
                return '<i class="fa fa-check-square-o"></i>';
            case self::NO :
                return '<i class="fa fa-square-o"></i>';
            case self::NOT_RUN :
                return '<i class="fa fa-ban"></i>';
            case self::INCOMPATIBLE :
                return '<i class="fa fa-remove"></i>';
            default :
                return '&nbsp;';
        }
    }

    private function Bugfixes_cve($cve) {
        if (!empty($cve)) {
            if (strpos($cve, ', ') !== false) {
                $cves = explode(', ', $cve);
                $cveHtml = array();
                foreach($cves as $cve) {
                    $cveHtml[] = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
                }
                $cveHtml = implode(',<br />', $cveHtml);
            } else {
                $cveHtml = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
            }
        } else {
            $cveHtml = '-';
        }

        return $cveHtml;
    }

    private function Compatibility($count, $analyzer) {
        if ($count == Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-ban" style="color: orange"></i>';
        } elseif ($count == Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-cogs" style="color: orange"></i>';
        } elseif ($count === 0) {
            return '<i class="fa fa-check-square-o" style="color: green"></i>';
        } else {
            return '<i class="fa fa-warning" style="color: red"></i>&nbsp;<a href="compatibility_issues.html#analyzer='.$analyzer.'">'.$count.' warnings</a>';
        }
    }
    
    protected function toId($name) {
        return str_replace('/', '_', strtolower($name));
    }
    
    protected function makeAuditDate(&$finalHTML) {
        $audit_date = 'Audit date : '.date('d-m-Y h:i:s', time());
        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $audit_date .= ' - &quot;'.$audit_name.'&quot;';
        }
        $finalHTML = $this->injectBloc($finalHTML, 'AUDIT_DATE', $audit_date);
    }
}

?>