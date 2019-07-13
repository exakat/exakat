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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;
use Exakat\Reports\Helpers\Results;

class Grade extends Ambassador {
    const FILE_FILENAME  = 'grade';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Grade';

    const COLORS = array('A' => '#2ED600',
                         'B' => '#81D900',
                         'C' => '#D5DC00',
                         'D' => '#DF9100',
                         'E' => '#E23E00',
                         'F' => '#E50016',
                         );

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    protected $tmpName           = '';
    private $globalGrade = 0;

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    const G_CRITICAL = 5;
    const G_ERROR    = 4;
    const G_WARNING  = 3;
    const G_NOTICE   = 2;
    const G_NONE     = 1;

    private $grading = array();
    private $results = null;
    private $resultsCounts = null;

    public function __construct($config) {
        parent::__construct($config);
        $this->themesToShow      = array('Security');
        
        $this->grading = array(
    'Security/AnchorRegex'                  => self::G_WARNING,
    'Security/EncodedLetters'               => self::G_WARNING,
    'Structures/EvalWithoutTry'             => self::G_CRITICAL,
    'Security/parseUrlWithoutParameters'    => self::G_CRITICAL,
    'Structures/pregOptionE'                => self::G_NOTICE,
    'Indirect Injection'                    => self::G_NOTICE,
    'Security/IndirectInjection'            => self::G_NOTICE,
    'Structures/EvalUsage'                  => self::G_ERROR,
    'Security/Sqlite3RequiresSingleQuotes'  => self::G_ERROR,
        );
    }
    
    public function dependsOnAnalysis() {
        return array('Security',
                     );
    }

    private function generateIssues(Section $section) {
        $this->generateIssuesEngine($section,
                                    $this->getIssuesFaceted($section->ruleset));
    }

    private function getGrades() {
        $this->results = new Results($this->sqlite, array_keys($this->grading));
        $this->results->load();

        $this->resultsCounts = array_fill_keys(array_keys($this->grading), 0);
        foreach($this->results->toArray() as $result) {
            $this->resultsCounts[$result['analyzer']]++;
        }

        $this->globalGrade = 0;
        
        $grade = 0;
        foreach($this->resultsCounts as $name => $value) {
            if ($value > 0) {
                $grade += min(log($value * $this->grading[$name]) / log(10), 5);
            }
        }
        $this->globalGrade = intval(100 * max(0, 20 - $grade)) / 100;
    }

    protected function generateDashboard(Section $section) {
        $this->getGrades();

        $baseHTML = $this->getBasedPage('index');

        $tags = array();
        $code = array();

        // Bloc top left
        $grade = <<<HTML
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Global grade : $this->globalGrade / 20</h3>
                </div>
                <div class="box-body chart-responsive">
                  <div id="donut-chart_grade"></div>
                </div>
                <!-- /.box-body -->
              </div>

HTML;
        $finalHTML = $this->injectBloc($baseHTML, 'BLOCHASHDATA', $grade);

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
        $fileHTML = $this->getTopFile($this->themesToShow);
        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers($this->themesToShow);
        $finalHTML = $this->injectBloc($finalHTML, 'TOPANALYZER', $analyzerHTML);
        
        $globalData = array(self::G_CRITICAL  => (object) array('label' => 'Critical', 'value' => 0),
                            self::G_ERROR     => (object) array('label' => 'Error',    'value' => 0),
                            self::G_WARNING   => (object) array('label' => 'Warning',  'value' => 0),
                            self::G_NOTICE    => (object) array('label' => 'Notice',   'value' => 0),
                            self::G_NONE      => (object) array('label' => 'OK',       'value' => 0));
        foreach($this->resultsCounts as $name => $value) {
            if ($value > 0) {
                $globalData[$this->grading[$name]]->value += floor(100 * min(log($value * $this->grading[$name]) / log(10), 5)) / 100;
            }
        }
        unset($globalData[self::G_NONE]);
        
        $globalData = json_encode(array_values($globalData));

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
      Morris.Donut({
        element: 'donut-chart_grade',
        resize: true,
        colors: ["#0010E5", "#00DBC5", "#1BD200", "#C8A800", "#BF0023"],
        data: $globalData
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
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Grading code');
        $this->putBasedPage('index', $finalHTML);
    }

}

?>