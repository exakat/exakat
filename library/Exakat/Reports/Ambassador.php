<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Datastore;
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Ambassador extends Reports {

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    private $tmpName           = '';
    
    private $docs              = null;
    private $timesToFix        = null;
    private $themesForAnalyzer = null;
    private $severities        = null;

    private $themesToShow = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 
                                  'CompatibilityPHP70', 'CompatibilityPHP71',
                                  '"Dead code"', 'Security', 'Analyze');

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->docs              = new Docs($this->config->dir_root.'/data/analyzers.sqlite');
        $this->timesToFix        = $this->docs->getTimesToFix();
        $this->themesForAnalyzer = $this->docs->getThemesForAnalyzer();
        $this->severities        = $this->docs->getSeverities();
    }

    /**
     * Get the base file
     *
     * @param type $file
     */
    private function getBasedPage($file) {
        static $baseHTML;
        
        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'TITLE', $title);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = file_get_contents($this->tmpName . '/datas/menu.html');
            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
        }

        $subPageHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/' . $file . '.html');
        $combinePageHTML = $this->injectBloc($baseHTML, "BLOC-MAIN", $subPageHTML);

        return $combinePageHTML;
    }

    private function injectBloc($html, $bloc, $content) {
        return str_replace("{{" . $bloc . "}}", $content, $html);
    }

    public function generateFileReport($report) {
        
    }

    public function generate($folder, $name = 'report') {
        $this->finalName = $folder . '/' . $name;
        $this->tmpName = $folder . '/.' . $name;

        $this->projectPath = $folder;
        
        $this->initFolder();
        $this->generateSettings();
        $this->generateProcFiles();  

        $this->generateDashboard();
        $this->generateFiles();
        $this->generateAnalyzers();
        $this->generateIssues();
        $this->generateAnalyzersList();
        $this->generateExternalLib();
        
        $this->generateAppinfo();
        $this->generateBugFixes();
        $this->generateExternalServices();
        $this->generateDirectiveList();
        $this->generateAlteredDirectives();
        $this->generateStats();
        
        // Favorites
        $this->generateFavorites();
        $this->generateDynamicCode();

        // inventories
        $this->generateErrorMessages();


        // Annex
        $this->generateDocumentation();
        $this->generateCodes();  

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            file_put_contents($this->tmpName . '/datas/'.$file.'.html', $baseHTML);
        }
        
        $this->cleanFolder();
    }

    /**
     * Clear and init folder
     *
     * @return string
     */
    private function initFolder() {
        if ($this->finalName === null) {
            return "Can't produce Devoops format to stdout";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root . '/media/devfaceted', $this->tmpName );
    }

    /**
     * Clear existant folder
     *
     */
    private function cleanFolder() {
        if (file_exists($this->tmpName . '/base.html')) {
            unlink($this->tmpName . '/base.html');
            unlink($this->tmpName . '/menu.html');
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName . " folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    private function getLinesFromFile($filePath,$lineNumber,$numberBeforeAndAfter){
        $lineNumber--; // array index
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

            for ($i=$startLine; $i < $endLine+1 ; $i++) {
                $lines[]= array(
                            "line"=>$i+1,
                            "code"=>$fileLines[$i]
                    );
            }
        }
        return $lines;
    }

    private function setPHPBlocs($description){
        $description = str_replace("<?php", '</p><pre><code class="php">&lt;?php', $description);
        $description = str_replace("?>", '?&gt;</code></pre><p>', $description);
        return $description;
    }

    private function generateDocumentation(){
        $datas = array();
        $baseHTML = $this->getBasedPage("analyzers_doc");
        $analyzersDocHTML = "";

        foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
            $analyzer = Analyzer::getInstance($analyzer);
            $description = $analyzer->getDescription();
            $analyzersDocHTML.='<h2><a href="issues.html?analyzer='.md5($description->getName()).'">'.$description->getName().'</a></h2>';
            
            $badges = array();
            $v = $description->getVersionAdded();
            if(!empty($v)){
                $badges[] = '[Since '.$v.']';
            }
            $badges[] = '[ -P '.$analyzer->getInBaseName().' ]';

            $versionCompatibility = $analyzer->getPhpversion();
            if ($versionCompatibility !== Analyzer::PHP_VERSION_ANY) {
                if (strpos($versionCompatibility, '+') !== false) {
                    $versionCompatibility = substr($versionCompatibility, 0, -1) . ' and more recent ';
                } elseif (strpos($versionCompatibility, '-') !== false) {
                    $versionCompatibility = ' older than '.substr($versionCompatibility, 0, -1);
                } 
                $badges[] = '[ PHP '.$versionCompatibility.']';
            }
            
            $analyzersDocHTML .= '<p>'.implode(' - ', $badges).'</p>';
            $analyzersDocHTML.='<p>'.$this->setPHPBlocs($description->getDescription()).'</p>';

            $v = $description->getClearPHP();
            if(!empty($v)){
                $analyzersDocHTML.='<p>This rule is named <a target="_blank" href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$description->getClearPHP().'.md">'.$description->getClearPHP().'</a>, in the clearPHP reference.</p>';
            }
        }
        $finalHTML = $this->injectBloc($baseHTML, "BLOC-ANALYZERS", $analyzersDocHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/highlight.pack.js"></script>');

        file_put_contents($this->tmpName . '/datas/analyzers_doc.html', $finalHTML);
    }

    private function generateFavorites() {
        $baseHTML = $this->getBasedPage('favorites_dashboard');
        
        $analyzers = Analyzer::getThemeAnalyzers('Preferences');
        
        $donut = array();
        $html = array();
        
        foreach($analyzers as $analyzer) {
            $list = $this->datastore->getHashAnalyzer($analyzer);
        
            $table = '';
            $values = '';
            $object = Analyzer::getInstance($analyzer);
            $name = $object->getDescription()->getName();

            $total = 0;
            foreach($list as $key => $value) {
                $table .= '
                <div class="clearfix">
                   <div class="block-cell">'.htmlentities($key, ENT_COMPAT | ENT_HTML401, 'UTF-8').'</div>
                   <div class="block-cell text-center">'.$value.'</div>
                 </div>
';          
                $values[] = '{label:"'.$key.'", value:'.$value.'}';
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
                  <h3 class="box-title">$name</h3>
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
HTML
;       
            if (count($html) % 4 === 0) {
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
        $donut = implode("\n", $donut);
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
        $html = '<div class="row">'.implode("\n", $html).'</div>';

        $baseHTML = $this->injectBloc($baseHTML, "FAVORITES", $html);
        $baseHTML = $this->injectBloc($baseHTML, "BLOC-JS", $donut);
        file_put_contents($this->tmpName . '/datas/favorites_dashboard.html', $baseHTML);


        $baseHTML = $this->getBasedPage('favorites_issues');

        $preferencesJson = implode(', ', $this->getIssuesFaceted('Preferences'));
        $blocjs = <<<JAVASCRIPT
 <script src="facetedsearch.js"></script>


  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [$preferencesJson];
      var item_template =  
        '<tr>' +
          '<td width="20%"><%= obj.analyzer %></td>' +
          '<td width="20%"><%= obj.file + ":" + obj.line %></td>' +
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
      
      var analyzerParam = window.location.search.split('analyzer=')[1];
      var fileParam = window.location.search.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam + "']").click();
      }
    });
  </script>

JAVASCRIPT;

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        file_put_contents($this->tmpName . '/datas/favorites_issues.html', $finalHTML);
    }

    /**
     * generate the content of Dashboad
     */
    public function generateDashboard() {
        $baseHTML = $this->getBasedPage('index');
        
        $tags = array();
        $code = array();

        // Bloc top left
        $hashData = $this->getHashData();
        $finalHTML = $this->injectBloc($baseHTML, "BLOCHASHDATA", $hashData);

        // bloc Issues
        $issues = $this->getIssuesBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, "BLOCISSUES", $issues['html']);
        $tags[] = 'SCRIPTISSUES';
        $code[] = $issues['script'];

        // bloc severity
        $severity = $this->getSeverityBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, "BLOCSEVERITY", $severity['html']);
        $tags[] = 'SCRIPTSEVERITY';
        $code[] = $severity['script'];

        // top 10
        $fileHTML = $this->getTopFile();
        $finalHTML = $this->injectBloc($finalHTML, "TOPFILE", $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers();
        $finalHTML = $this->injectBloc($finalHTML, "TOPANALYZER", $analyzerHTML);
        
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
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS",  $blocjs);

        file_put_contents($this->tmpName . '/datas/index.html', $finalHTML);
    }

    /**
     * Get info bloc top left
     *
     * @return string
     */
    public function getHashData() {
        $php = new Phpexec($this->config->phpversion);

        $info = array(
            'Number of PHP files'                   => $this->datastore->getHash('files'),
            'Number of lines of code'               => $this->datastore->getHash('loc'),
            'Number of lines of code with comments' => $this->datastore->getHash('locTotal'),
            'PHP used' => $php->getActualVersion() //.' (version '.$this->config->phpversion.' configured)'
        );

        // fichier
        $totalFile = $this->datastore->getHash('files');
        $totalFileAnalysed = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFileAnalysed - $totalFile;
        $percentFile = abs(round($totalFileSansError / $totalFile * 100));

        // analyzer
        list($totalAnalyzerUsed, $totalAnalyzerReporting) = $this->getTotalAnalyzer();
        $totalAnalyzerWithoutError = $totalAnalyzerUsed - $totalAnalyzerReporting;
        $percentAnalyzer = abs(round($totalAnalyzerWithoutError / $totalAnalyzerUsed * 100));

        $html = '<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Project Overview</h3>
                    </div>

                    <div class="box-body chart-responsive">
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of PHP</span> files</p>
                                <p class="value">' . $info['Number of PHP files'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">' . $info['PHP used'] . '</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>Total</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code with comments'] . '</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Files free of issues (%)</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentFile . '%">
                                        '.$totalFileSansError.'
                                    </div><div style="color:black; text-align:center;">'.$totalFileAnalysed.'</div>
                                </div>
                                <div class="pourcentage">' . $percentFile . '%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Analyzers free of issues (%)</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentAnalyzer . '%">
                                        '.$totalAnalyzerWithoutError.'
                                    </div><div style="color:black; text-align:center;">'.$totalAnalyzerReporting.'</div>
                                </div>
                                <div class="pourcentage">' . $percentAnalyzer . '%</div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    /**
     * Get Issues Breakdown
     *
     */
    public function getIssuesBreakdown() {
        $receipt = array('Code Smells'  => 'Analyze',
                         'Dead Code'    => 'Dead code',
                         'Security'     => 'Security',
                         'Performances' => 'Performances');

        $data = array();
        foreach ($receipt AS $key => $categorie) {
            $list = 'IN ("'.implode('", "', Analyzer::getThemeAnalyzers($categorie)).'")';
            $query = "SELECT sum(count) FROM resultsCounts WHERE analyzer $list AND count > 0";
            $total = $this->sqlite->querySingle($query);

            $data[] = array('label' => $key, 'value' => $total);
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
        $dataScript = '';

        foreach ($data as $key => $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= ($dataScript) ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html' => $issuesHtml, 'script' => $dataScript);
    }

    /**
     * Severity Breakdown
     *
     */
    public function getSeverityBreakdown() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = <<<SQL
                SELECT severity, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY severity
                    ORDER BY number DESC
SQL;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $data[] = array('label' => $row['severity'], 
                            'value' => $row['number']);
        }
        
        $html = '';
        $dataScript = '';
        foreach ($data as $key => $value) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= ($dataScript) ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html' => $html, 'script' => $dataScript);
    }

    /**
     * Liste fichier analysé
     *
     */
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

        $baseHTML = $this->getBasedPage("analyzers");
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML.= "<tr>";
            $analyserHTML.='<td>' . $analyser["label"] . '</td>
                        <td>' . $analyser["recipes"] . '</td>
                        <td>' . $analyser["issues"] . '</td>
                        <td>' . $analyser["files"] . '</td>
                        <td>' . $analyser["severity"] . '</td>';
            $analyserHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-ANALYZERS", $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        file_put_contents($this->tmpName . '/datas/analyzers.html', $finalHTML);
    }

    protected function getAnalyzersResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS issues, count(distinct file) AS files, severity AS severity FROM results
        WHERE analyzer IN ($list)
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );

        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = Analyzer::getInstance($row['analyzer']);
            $row['label'] = $analyzer->getDescription()->getName();
            $row['recipes' ] =  join(', ', $this->themesForAnalyzer[$row['analyzer']]);

            $return[] = $row;
        }

        return $return;
    }

    /**
     * Nombre fichier qui ont l'analyzer
     *
     * @param type $analyzer
     */
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

    /**
     * generate the content of liste files
     */
    private function generateFiles() {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage("files");
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= "<tr>";
            $filesHTML.='<td>' . $file["file"] . '</td>
                        <td>' . $file["loc"] . '</td>
                        <td>' . $file["issues"] . '</td>
                        <td>' . $file["analyzers"] . '</td>';
            $filesHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-FILES", $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        file_put_contents($this->tmpName . '/datas/files.html', $finalHTML);
    }

    /**
     * Get list of file
     *
     */
    private function getFilesResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

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

    /**
     * Nombre analyzer par fichier
     *
     * @param type $file
     */
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

    /**
     * List file with count
     *
     * @param type $limit
     */
    public function getFilesCount($limit = null) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT file, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY file
                    ORDER BY number DESC ";
        if ($limit !== null) {
            $query .= " LIMIT " . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $data[] = array('file' => $row['file'], 'value' => $row['number']);
        }

        return $data;
    }

    /**
     * Liste de top file
     *
     */
    private function getTopFile() {
        $data = $this->getFilesCount(self::TOPLIMIT);

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="' . $value['file'] . '">
                      <div class="block-cell-name">' . $value['file'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                    </a>
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

    /**
     * Get data files overview
     * 
     */
    private function getFileOverview() {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();
        $severities = $this->getSeveritiesNumberBy('file');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['file'] . "'";
            $dataCritical[] = empty($severities[$value['file']]['Critical']) ? 0 : $severities[$value['file']]['Critical'];
            $dataMajor[]    = empty($severities[$value['file']]['Major'])    ? 0 : $severities[$value['file']]['Major'];
            $dataMinor[]    = empty($severities[$value['file']]['Minor'])    ? 0 : $severities[$value['file']]['Minor'];
            $dataNone[]     = empty($severities[$value['file']]['None'])     ? 0 : $severities[$value['file']]['None'];
        }
        $xAxis        = join(', ', $xAxis);
        $dataCritical = join(', ', $dataCritical);
        $dataMajor    = join(', ', $dataMajor);
        $dataMinor    = join(', ', $dataMinor);
        $dataNone     = join(', ', $dataNone);

        return array(
            'scriptDataFiles' => $xAxis,
            'scriptDataMajor' => $dataMajor,
            'scriptDataCritical' => $dataCritical,
            'scriptDataNone' => $dataNone,
            'scriptDataMinor' => $dataMinor
        );
    }

    /**
     * List analyzer with count
     *
     * @param type $limit
     */
    private function getAnalyzersCount($limit) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC ";
        if ($limit) {
            $query .= " LIMIT " . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('analyzer' => $row['analyzer'], 'value' => $row['number']);
        }

        return $data;
    }

    /**
     * Liste de top analyzers
     *
     */
    private function getTopAnalyzers() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC
                    LIMIT " . self::TOPLIMIT;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $analyzer = Analyzer::getInstance($row['analyzer']);
            $data[] = array('label' => $analyzer->getDescription()->getName(), 'value' => $row['number']);
        }

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="' . $value['label'] . '">
                      <div class="block-cell-name">' . $value['label'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                    </a>
                  </div>';
        }

        return $html;
    }

    /**
     * Nombre severity by file en Dashboard
     *
     */
    private function getSeveritiesNumberBy($type = 'file') {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY $type, severity
SQL;

        $stmt = $this->sqlite->query($query);

        $return = array();
        while ($row = $stmt->fetchArray(\SQLITE3_ASSOC) ) {
            if ( !isset($return[$row[$type]]) ) {
                $return[$row[$type]] = array($row['severity'] => $row['count']);
            } else {
                $return[$row[$type]][$row['severity']] = $row['count'];
            }
        }

        return $return;
    }
    
    /**
     * Get data analyzer overview
     * 
     */
    private function getAnalyzerOverview() {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['analyzer'] . "'";
            $dataCritical[] = empty($severities[$value['analyzer']]['Critical']) ? 0 : $severities[$value['analyzer']]['Critical'];
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major'])    ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor'])    ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None'])     ? 0 : $severities[$value['analyzer']]['None'];
        }
        $xAxis = join(', ', $xAxis);
        $dataCritical = join(', ', $dataCritical);
        $dataMajor = join(', ', $dataMajor);
        $dataMinor = join(', ', $dataMinor);
        $dataNone = join(', ', $dataNone);

        return array(
            'scriptDataAnalyzer'         => $xAxis,
            'scriptDataAnalyzerMajor'    => $dataMajor,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerNone'     => $dataNone,
            'scriptDataAnalyzerMinor'    => $dataMinor
        );
    }
    
    /**
     * generate the content of Issues
     */
    private function generateIssues()
    {
        $baseHTML = $this->getBasedPage('issues');

        $issues = implode(', ', $this->getIssuesFaceted($this->themesToShow));
        $blocjs = <<<JAVASCRIPT
        
  <script src="facetedsearch.js"></script>
  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [$issues];
      var item_template =  
        '<tr>' +
          '<td width="20%"><%= obj.analyzer %></td>' +
          '<td width="20%"><%= obj.file + ":" + obj.line %></td>' +
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
      
      var analyzerParam = window.location.search.split('analyzer=')[1];
      var fileParam = window.location.search.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam + "']").click();
      }
    });
  </script>
JAVASCRIPT;

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        file_put_contents($this->tmpName . '/datas/issues.html', $finalHTML);
    }

    /**
     * List of Issues faceted
     * @return array
     */
    public function getIssuesFaceted($theme) {
        $list = Analyzer::getThemeAnalyzers($theme);
        $list = '"'.join('", "', $list).'"';

        $sqlQuery = <<<SQL
            SELECT fullcode, file, line, analyzer
                FROM results
                WHERE analyzer IN ($list)

SQL;
        $result = $this->sqlite->query($sqlQuery);

        $items = array();
        while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $item = array();
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $item['analyzer'] =  $ini['name'];
            $item['analyzer_md5'] = md5($ini['name']);
            $item['file' ] =  $row['file'];
            $item['file_md5' ] =  md5($row['file']);
            $item['code' ] = $row['fullcode'];
            $item['code_detail'] = "<i class=\"fa fa-plus \"></i>";
            $item['code_plus'] = htmlentities($row['fullcode'], ENT_COMPAT | ENT_HTML401 , 'UTF-8');
            $item['link_file'] = $row['file'];
            $item['line' ] =  $row['line'];
            $item['severity'] = "<i class=\"fa fa-warning " . $this->severities[$row['analyzer']] . "\"></i>";
            $item['complexity'] = "<i class=\"fa fa-cog " . $this->timesToFix[$row['analyzer']] . "\"></i>";
            $item['recipe' ] =  join(', ', $this->themesForAnalyzer[$row['analyzer']]);
            $lines = explode("\n", $ini['description']);
            $item['analyzer_help' ] = $lines[0];

            $items[] = json_encode($item);
            $this->count();
        }

        return $items;
    }
    
    /**
     * Get class by type
     * 
     * @param type $type
     * @return string
     */
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

        $info[] = array('Report production date', date('r', strtotime('now')));
        
        $php = new Phpexec($this->config->phpversion);
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));
        
        $info[] = array('Exakat version', Exakat::VERSION. ' ( Build '. Exakat::BUILD . ') ');
        
        $settings = '';
        foreach($info as $i) {
            $settings .= "<tr><td>$i[0]</td><td>$i[1]</td></tr>";
        }        
        
        $html = $this->getBasedPage('used_settings');
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        file_put_contents($this->tmpName.'/datas/used_settings.html', $html);
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
        file_put_contents($this->tmpName.'/datas/proc_files.html', $html);
    }

    private function generateAnalyzersList() {
        $analyzers = '';

       foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
           $analyzer = Analyzer::getInstance($analyzer);
           $description = $analyzer->getDescription();
    
           $analyzers .= "<tr><td>".$description->getName()."</td></tr>\n";
        }

        $html = $this->getBasedPage('proc_analyzers');
        $html = $this->injectBloc($html, 'ANALYZERS', $analyzers);
        file_put_contents($this->tmpName.'/datas/proc_analyzers.html', $html);
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
        file_put_contents($this->tmpName.'/datas/ext_lib.html', $html);
    }

    protected function generateBugfixes() {
        $table = '';

        $data = new Methods();
        $bugfixes = $data->getBugFixes();
        
        $found = $this->sqlite->query('SELECT * FROM results WHERE analyzer = "Php/MiddleVersion"');
        $reported = array();
        $info = array();

        $rows = array();
        while($row = $found->fetchArray()) {
            $rows[strtolower(substr($row['fullcode'], 0, strpos($row['fullcode'], '(')))] = $row;
        }
        
        foreach($bugfixes as $bugfix) {
            if (!empty($bugfix['function'])) {
                if (!isset($rows[$bugfix['function']])) { continue; }

                $cve = $this->Bugfixes_cve($bugfix['cve']);
                $table .= '<tr>
    <td>'.$bugfix['title'].'</td>
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
        file_put_contents($this->tmpName.'/datas/bugfixes.html', $html);
    }

    private function generateErrorMessages() {
        $errorMessages = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/ErrorMessages"');
        while($row = $res->fetchArray()) {
            $errorMessages .= "<tr><td>$row[fullcode]</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('error_messages');
        $html = $this->injectBloc($html, 'ERROR_MESSAGES', $errorMessages);
        file_put_contents($this->tmpName.'/datas/error_messages.html', $html);
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
        file_put_contents($this->tmpName.'/datas/external_services.html', $html);
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
            analyzer IN ("Structures/FileUploadUsage", "Php/UsesEnv"))
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
        file_put_contents($this->tmpName.'/datas/directive_list.html', $html);
    }
    
    private function generateDynamicCode() {
        $dynamicCode = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/DynamicCode"');
        while($row = $res->fetchArray()) {
            $dynamicCode .= "<tr><td>$row[fullcode]</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('dynamic_code');
        $html = $this->injectBloc($html, 'DYNAMIC_CODE', $dynamicCode);
        file_put_contents($this->tmpName.'/datas/dynamic_code.html', $html);
    }
    
    private function generateAlteredDirectives() {
        $alteredDirectives = '';
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Php/DirectivesUsage"');
        while($row = $res->fetchArray()) {
            $alteredDirectives .= "<tr><td>$row[fullcode]</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }
        
        $html = $this->getBasedPage('altered_directives');
        $html = $this->injectBloc($html, 'ALTERED_DIRECTIVES', $alteredDirectives);
        file_put_contents($this->tmpName.'/datas/altered_directives.html', $html);
    }    
    
    private function generateStats() {
        $extensions = array(
                    'Summary' => array(
                            'Namespaces'     => 'Namespace',
                            'Classes'        => 'Class',
                            'Interfaces'     => 'Interface',
                            'Trait'          => 'Trait',
                            'Function'       => 'Functions/RealFunctions',
                            'Variables'      => 'Variables/RealVariables',
                            'Constants'      => 'Constants/Constantnames',
                     ),
                    'Classes' => array(
                            'Classes'           => 'Class',
                            'Class constants'   => 'Classes/ConstantDefinition',
                            'Properties'        => 'Classes/NormalProperties',
                            'Static properties' => 'Classes/StaticProperties',
                            'Methods'           => 'Classes/NormalMethods',
                            'Static methods'    => 'Classes/StaticMethods',
                            // Spot Abstract methods
                            // Spot Final Methods 
                     ),
                    'Structures' => array(
                            'Ifthen'              => 'Ifthen',
                            'Else'                => 'Structures/ElseUsage',
                            'Switch'              => 'Switch',
                            'Case'                => 'Case',
                            'Default'             => 'Default',
                            'For'                 => 'For',
                            'Foreach'             => 'Foreach',
                            'While'               => 'While',
                            'Do..while'           => 'Dowhile',
                            'New'                 => 'New',
                            'Clone'               => 'Clone',
                            'Throw'               => 'Throw',
                            'Try'                 => 'Try',
                            'Catch'               => 'Catch',
                            'Finally'             => 'Finally',
                            'Yield'               => 'Yield',
                            '?  :'                => 'Ternary',
                            '?: '                 => 'Php/Coalesce',
                            '??'                  => 'Php/NullCoalesce',
                            'Variables constants' => 'Constants/VariableConstants',
                            'Variables variables' => 'Variables/VariableVariable',
                            'Variables functions' => 'Functions/Dynamiccall',
                            'Variables classes'   => 'Classes/VariableClasses',
                    ),
                );

        $stats = '';
        foreach($extensions as $section => $hash) {
            $stats .= "<tr><td colspan=2 bgcolor=#BBB>$section</td></tr>\n";

            foreach($hash as $name => $ext) {
                if (strpos($ext, '/') === false) {
                    $res = $this->sqlite->query('SELECT count FROM atomsCounts WHERE atom="'.$ext.'"'); 
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                } else {
                    $res = $this->sqlite->query('SELECT count FROM resultsCounts WHERE analyzer="'.$ext.'"'); 
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                }
                $res = $d === -2 ? 'N/A' : $d;
                $stats .= "<tr><td>$name</td><td>$res</td></tr>\n";
            }
        }
        
        $html = $this->getBasedPage('stats');
        $html = $this->injectBloc($html, 'STATS', $stats);
        file_put_contents($this->tmpName.'/datas/stats.html', $html);
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

            $source = show_source(dirname($this->tmpName).'/code/'.$row['file'], true);
            $files .= '<li><a href="#" id="'.$id.'" class="menuitem">'.htmlentities($row['file'], ENT_COMPAT | ENT_HTML401 , 'UTF-8')."</a></li>\n";
            file_put_contents($this->tmpName.'/datas/sources/'.$row['file'], substr($source, 6, -8));
        }

        $blocjs = <<<JAVASCRIPT
  <script src="facetedsearch.js"></script>


  <script>
  "use strict";

  $('.menuitem').click(function(event){
    $('#results').load("sources/" + event.target.text);
    $('#filename').html(event.target.text + '  <span class="caret"></span>');
  });

  var fileParam = window.location.search.split('file=')[1];
  if(fileParam !== undefined) {
    $('#results').load("sources/" + fileParam);
    $('#filename').html(fileParam + '  <span class="caret"></span>');
  }
  

  </script>
JAVASCRIPT;
        $html = $this->getBasedPage('codes');
        $html = $this->injectBloc($html, 'BLOC-JS', $blocjs);
        $html = $this->injectBloc($html, 'FILES', $files);
        
        file_put_contents($this->tmpName.'/datas/codes.html', $html);
    }
    
    private function generateAppinfo() {
        $extensions = array(
                    'PHP' => array(
                            'Short tags'                 => 'Structures/ShortTags',
                            'Echo tags <?='              => 'Php/EchoTagUsage',
                            'Incompilable'               => 'Php/Incompilable',
                            
                            '@ operator'                 => 'Structures/Noscream',
                            'Alternative syntax'         => 'Php/AlternativeSyntax',
                            'Magic constants'            => 'Constants/MagicConstantUsage',
                            'halt compiler'              => 'Php/Haltcompiler',
                            'Assertions'                 => 'Php/AssertionUsage',
          
                            'Casting'                    => 'Php/CastingUsage',
                            'Resources'                  => 'Structures/ResourcesUsage',
                            'Nested Loops'               => 'Structures/NestedLoops',
            
                            'Autoload'                   => 'Php/AutoloadUsage',
                            'inclusion'                  => 'Structures/IncludeUsage',
                            'include_once'               => 'Structures/OnceUsage',
                            'Output control'             => 'Extensions/Extob',
          
                            'Goto'                       => 'Php/Gotonames',
                            'Labels'                     => 'Php/Labelnames',

                            'Coalesce'                   => 'Php/Coalesce',
                            'Null Coalesce'              => 'Php/NullCoalesce',

                            'File upload'                => 'Structures/FileUploadUsage',
                            'Environnement Variables'    => 'Php/UsesEnv',
                    ),

                    'Composer' => array(
                            'composer.json'              => 'Composer/UseComposer',
                            'composer.lock'              => 'Composer/UseComposerLock',
                            'composer autoload'          => 'Composer/Autoload',
                    ),

                    'Web' => array(
                            '$_GET, _POST...'            => 'Php/UseWeb',
                            'Apache'                     => 'Extensions/Extapache',
                            'Fast CGI'                   => 'Extensions/Extfpm',
                            'IIS'                        => 'Extensions/Extiis',
                            'NSAPI'                      => 'Extensions/Extnsapi',
                    ),

                    'CLI' => array(
                            '$argv, $argc'                 => 'Php/UseCli',
                            'CLI script'                   => 'Files/IsCliScript',
                            'Ncurses'                      => 'Extensions/Extncurses',
                            'Newt'                         => 'Extensions/Extnewt',
                            'Readline'                     => 'Extensions/Extreadline',
                    ),

                    // filled later
                    'Composer Packages' => array(),

                    'Namespaces' => array(
                            'Namespaces'              => 'Namespaces/Namespacesnames',
                            'Alias'                   => 'Namespaces/Alias',
                    ),

                    'Variables' => array(
                            'References'              => 'Variables/References',
                            'Array'                   => 'Arrays/Arrayindex',
                            'Multidimensional arrays' => 'Arrays/Multidimensional',
                            'Array short syntax'      => 'Arrays/ArrayNSUsage',
                            'List short syntax'       => 'Structures/ListShortSyntax',
                            'Variable variables'      => 'Variables/VariableVariables',

                            'PHP arrays'              => 'Arrays/Phparrayindex',

                            'Globals'                 => 'Structures/GlobalUsage',
                            'PHP SuperGlobals'        => 'Php/SuperGlobalUsage',
                    ),

                    'Functions' => array(
                            'Functions'                => 'Functions/Functionnames',
                            'Redeclared PHP Functions' => 'Functions/RedeclaredPhpFunction',
                            'Closures'             => 'Functions/Closures',

                            'Typehint'             => 'Functions/Typehints',
                            'Scalar Typehint'      => 'Php/ScalarTypehintUsage',
                            'Return Typehint'      => 'Php/ReturnTypehintUsage',
                            'Nullable Typehint'    => 'Php/UseNullableType',
                            'Static variables'     => 'Variables/StaticVariables',

                            'Function dereferencing'     => 'Structures/FunctionSubscripting',
                            'Constant scalar expression' => 'Structures/ConstantScalarExpression',
                            '... usage'                  => 'Php/EllipsisUsage',
                            'func_get_args'              => 'Functions/VariableArguments',

                            'Dynamic functioncall' => 'Functions/Dynamiccall',

                            'Recursive Functions'  => 'Functions/Recursive',
                            'Generator Functions'  => 'Functions/IsGenerator',
                            'Conditioned Function' => 'Functions/ConditionedFunctions',
                    ),

                    'Classes' => array(
                            'Classes'           => 'Classes/Classnames',
                            'Anonymous Classes' => 'Classes/Anonymous',
                            'Class aliases'     => 'Classes/ClassAliasUsage',

                            'Abstract classes'  => 'Classes/Abstractclass',
                            'Interfaces'        => 'Interfaces/Interfacenames',
                            'Traits'            => 'Traits/Traitnames',

                            'Static properties' => 'Classes/StaticProperties',
                            
                            'Static methods'    => 'Classes/StaticMethods',
                            'Abstract methods'  => 'Classes/Abstractmethods',
                            'Final methods'     => 'Classes/Finalmethod',

                            'Class constants'   => 'Classes/ConstantDefinition',
                            'Overwritten constants' => 'Classes/OverwrittenConst',

                            'Magic methods'     => 'Classes/MagicMethod',
                            'Cloning'           => 'Classes/CloningUsage',
                            'Dynamic class call'=> 'Classes/VariableClasses',

                            'PHP 4 constructor' => 'Classes/OldStyleConstructor',
                            'Multiple class in one file' => 'Classes/MultipleClassesInFile',
                    ),

                    'Constants' => array(
                            'Constants'           => 'Constants/ConstantUsage',
                            'Boolean'             => 'Type/BooleanValue',
                            'Null'                => 'Type/NullValue',
                            'Variable Constant'   => 'Constants/VariableConstant',
                            'PHP constants'       => 'Constants/PhpConstantUsage',
                            'PHP Magic constants' => 'Constants/MagicConstantUsage',
                            'Conditioned constant'=> 'Constants/ConditionedConstants',
                    ),

                    'Numbers' => array(
                            'Integers'            => 'Type/Integer',
                            'Hexadecimal'         => 'Type/Hexadecimal',
                            'Octal'               => 'Type/Octal',
                            'Binary'              => 'Type/Binary',
                            'Real'                => 'Type/Real',
                    ),

                    'Strings' => array(
                            'Heredoc'             => 'Type/Heredoc',
                            'Nowdoc'              => 'Type/Nowdoc',
                     ),
                    
                    'Errors' => array(   
                            'Throw exceptions'    => 'Php/ThrowUsage',
                            'Try...Catch'         => 'Php/TryCatchUsage',
                            'Multiple catch'      => 'Structures/MultipleCatch',
                            'Multiple Exceptions' => 'Exceptions/MultipleCatch',
                            'Finally'             => 'Structures/TryFinally',
                            'Trigger error'       => 'Php/TriggerErrorUsage',
                            'Error messages'      => 'Structures/ErrorMessages',
                     ),

                    'External systems' => array(
                            'System'           => 'Structures/ShellUsage',
                            'Files'            => 'Structures/FileUsage',
                            'LDAP'             => 'Extensions/Extldap',
                            'mail'             => 'Structures/MailUsage',
                     ),

                    'Extensions' => array(
                            'ext/amqp'       => 'Extensions/Extamqp',
                            'ext/apache'     => 'Extensions/Extapache',
                            'ext/apc'        => 'Extensions/Extapc',
                            'ext/apcu'       => 'Extensions/Extapcu',
                            'ext/array'      => 'Extensions/Extarray',
                            'ext/ast'        => 'Extensions/Extast',
                            'ext/bcmath'     => 'Extensions/Extbcmath',
                            'ext/bzip2'      => 'Extensions/Extbzip2',
                            'ext/cairo'      => 'Extensions/Extcairo',
                            'ext/calendar'   => 'Extensions/Extcalendar',
                            'ext/com'        => 'Extensions/Extcom',
                            'ext/crypto'     => 'Extensions/Extcrypto',
                            'ext/ctype'      => 'Extensions/Extctype',
                            'ext/curl'       => 'Extensions/Extcurl',
                            'ext/cyrus'      => 'Extensions/Extcyrus',
                            'ext/date'       => 'Extensions/Extdate',
                            'ext/dba'        => 'Extensions/Extdba',
                            'ext/dio'        => 'Extensions/Extdio',
                            'ext/dom'        => 'Extensions/Extdom',
                            'ext/eaccelerator' => 'Extensions/Exteaccelerator',
                            'ext/enchant'    => 'Extensions/Extenchant',
                            'ext/ereg'       => 'Extensions/Extereg',
                            'ext/event'      => 'Extensions/Extevent',
                            'ext/ev'         => 'Extensions/Extev',
                            'ext/exif'       => 'Extensions/Extexif',
                            'ext/expect'     => 'Extensions/Extexpect',
                            'ext/fann'       => 'Extensions/Extfann',
                            'ext/fdf'        => 'Extensions/Extfdf',
                            'ext/ffmpeg'     => 'Extensions/Extffmpeg',
                            'ext/file'       => 'Extensions/Extfile',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo',
                            'ext/filter'     => 'Extensions/Extfilter',
                            'ext/fpm'        => 'Extensions/Extfpm',
                            'ext/ftp'        => 'Extensions/Extftp',
                            'ext/gd'         => 'Extensions/Extgd',
                            'ext/gearman'    => 'Extensions/Extgearman',
                            'ext/geoip'      => 'Extensions/Extgeoip',
                            'ext/gettext'    => 'Extensions/Extgettext',
                            'ext/gmagick'    => 'Extensions/Extgmagick',
                            'ext/gmp'        => 'Extensions/Extgmp',
                            'ext/gnupg'      => 'Extensions/Extgnupg',
                            'ext/hash'       => 'Extensions/Exthash',
                            'ext/php_http'   => 'Extensions/Exthttp',
                            'ext/ibase'      => 'Extensions/Extibase',
                            'ext/iconv'      => 'Extensions/Exticonv',
                            'ext/iis'        => 'Extensions/Extiis',
                            'ext/imagick'    => 'Extensions/Extimagick',
                            'ext/imap'       => 'Extensions/Extimap',
                            'ext/info'       => 'Extensions/Extinfo',
                            'ext/inotify'    => 'Extensions/Extinotify',
                            'ext/intl'       => 'Extensions/Extintl',
                            'ext/json'       => 'Extensions/Extjson',
                            'ext/kdm5'       => 'Extensions/Extkdm5',
                            'ext/ldap'       => 'Extensions/Extldap',
                            'ext/libevent'   => 'Extensions/Extlibevent',
                            'ext/libxml'     => 'Extensions/Extlibxml',
                            'ext/mail'       => 'Extensions/Extmail',
                            'ext/mailparse'  => 'Extensions/Extmailparse',
                            'ext/math'       => 'Extensions/Extmath',
                            'ext/mbstring'   => 'Extensions/Extmbstring',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt',
                            'ext/memcache'   => 'Extensions/Extmemcache',
                            'ext/memcached'  => 'Extensions/Extmemcached',
                            'ext/ming'       => 'Extensions/Extming',
                            'ext/mongo'      => 'Extensions/Extmongo',
                            'ext/mssql'      => 'Extensions/Extmssql',
                            'ext/mysql'      => 'Extensions/Extmysql',
                            'ext/mysqli'     => 'Extensions/Extmysqli',
                            'ext/ob'         => 'Extensions/Extob',
                            'ext/oci8'       => 'Extensions/Extoci8',
                            'ext/odbc'       => 'Extensions/Extodbc',
                            'ext/opcache'    => 'Extensions/Extopcache',
                            'ext/openssl'    => 'Extensions/Extopenssl',
                            'ext/parsekit'   => 'Extensions/Extparsekit',
                            'ext/pcntl'      => 'Extensions/Extpcntl',
                            'ext/pcre'       => 'Extensions/Extpcre',
                            'ext/pdo'        => 'Extensions/Extpdo',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/phalcon'    => 'Extensions/Extphalcon',
                            'ext/phar'       => 'Extensions/Extphar',
                            'ext/posix'      => 'Extensions/Extposix',
                            'ext/proctitle'  => 'Extensions/Extproctitle',
                            'ext/pspell'     => 'Extensions/Extpspell',
                            'ext/readline'   => 'Extensions/Extreadline',
                            'ext/recode'     => 'Extensions/Extrecode',
                            'ext/redis'      => 'Extensions/Extredis',
                            'ext/reflexion'  => 'Extensions/Extreflection',
                            'ext/runkit'     => 'Extensions/Extrunkit',
                            'ext/sem'        => 'Extensions/Extsem',
                            'ext/session'    => 'Extensions/Extsession',
                            'ext/shmop'      => 'Extensions/Extshmop',
                            'ext/simplexml'  => 'Extensions/Extsimplexml',
                            'ext/snmp'       => 'Extensions/Extsnmp',
                            'ext/soap'       => 'Extensions/Extsoap',
                            'ext/sockets'    => 'Extensions/Extsockets',
                            'ext/spl'        => 'Extensions/Extspl',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',
                            'ext/sqlsrv'     => 'Extensions/Extsqlsrv',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/standard'   => 'Extensions/Extstandard',
                            'ext/tidy'       => 'Extensions/Exttidy',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer',
                            'ext/trader'     => 'Extensions/Exttrader',
                            'ext/wddx'       => 'Extensions/Extwddx',
                            'ext/wikidiff2'  => 'Extensions/Extwikidiff2',
                            'ext/wincache'   => 'Extensions/Extwincache',
                            'ext/xcache'     => 'Extensions/Extxcache',
                            'ext/xdebug'     => 'Extensions/Extxdebug',
                            'ext/xdiff'      => 'Extensions/Extxdiff',
                            'ext/xhprof'     => 'Extensions/Extxhprof',
                            'ext/xml'        => 'Extensions/Extxml',
                            'ext/xmlreader'  => 'Extensions/Extxmlreader',
                            'ext/xmlrpc'     => 'Extensions/Extxmlrpc',
                            'ext/xmlwriter'  => 'Extensions/Extxmlwriter',
                            'ext/xsl'        => 'Extensions/Extxsl',
                            'ext/yaml'       => 'Extensions/Extyaml',
                            'ext/yis'        => 'Extensions/Extyis',
                            'ext/zip'        => 'Extensions/Extzip',
                            'ext/zlib'       => 'Extensions/Extzlib',
                            'ext/zmq'        => 'Extensions/Extzmq',
//                          'ext/skeleton'   => 'Extensions/Extskeleton',
                    ),
                );

        // collecting information for Extensions
        $themed = Analyzer::getThemeAnalyzers('Appinfo');
        $res = $this->sqlite->query('SELECT analyzer, count FROM resultsCounts WHERE analyzer IN ("'.implode('", "', $themed).'")');
        $sources = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $sources[$row['analyzer']] = $row['count'];
        }
        $data = array();
        
        foreach($extensions as $section => $hash) {
            $data[$section] = array();

            foreach($hash as $name => $ext) {
                if (!isset($sources[$ext])) {
                    $data[$section][$name] = self::NOT_RUN;
                    continue;
                }
                if (!in_array($ext, $themed)) {
                    $data[$section][$name] = self::NOT_RUN;
                    continue;
                }
                
                // incompatible
                if ($sources[$ext] == Analyzer::CONFIGURATION_INCOMPATIBLE) {
                    $data[$section][$name] = self::INCOMPATIBLE;
                    continue ;
                } 

                if ($sources[$ext] == Analyzer::VERSION_INCOMPATIBLE) {
                    $data[$section][$name] = self::INCOMPATIBLE;
                    continue ;
                } 

                $data[$section][$name] = $sources[$ext] > 0 ? self::YES : self::NO;
            }
            
            if ($section == 'Extensions') {
                $list = $data[$section];
                uksort($data[$section], function ($ka, $kb) use ($list) {
                    if ($list[$ka] == $list[$kb]) {
                        if ($ka > $kb)  { return  1; }
                        if ($ka == $kb) { return  0; }
                        if ($ka > $kb)  { return -1; }
                    } else {
                        return $list[$ka] == self::YES ? -1 : 1;
                    }
                });
            }
        }
        // collecting information for Composer
        if (isset($sources['Composer/PackagesNames'])) {
            $data['Composer Packages'] = array();
            $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer = "Composer/PackagesNames"');
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $data['Composer Packages'][] = $row['fullcode'];
            }
        } else {
            unset($data['Composer Packages']);
        }
        
        $list = array();
        foreach($data as $section => $points) {
            $listPoint = array();
            foreach($points as $point => $status) {
                $listPoint[] = '<li>'.$this->makeIcon($status).'&nbsp;'.$point.'</li>';
            }

            $listPoint = implode("\n", $listPoint);
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
        file_put_contents($this->tmpName.'/datas/appinfo.html', $html);

    }

    protected function makeIcon($tag) {
        switch($tag) {
            case self::YES : 
                return '<i class="fa fa-check-square-o"></i>';
            case self::NO : 
                return '<i class="fa fa-square-o"></i>';
            case self::NOT_RUN : 
                return '<i class="fa fa-hourglass-o"></i>';
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
}

?>