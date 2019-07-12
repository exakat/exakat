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

class Weekly extends Ambassador {
    const FILE_FILENAME  = 'weekly';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Weekly';

    const COLORS = array('A' => '#2ED600',
                         'B' => '#81D900',
                         'C' => '#D5DC00',
                         'D' => '#DF9100',
                         'E' => '#E23E00',
                         'F' => '#E50016',
                         );

    protected $projectPath     = null;
    protected $finalName       = null;
    protected $tmpName           = '';
    private $globalGrade  = 0;

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

    private $titles = array('This Week',
                            'Last week',
                            'Two weeks ago',
                            'Three weeks ago',
                            'Four weeks ago',
                            'Go further',
                          );

    private $grading       = array();
    private $results       = null;
    private $resultsCounts = array();
    
    private $usedAnalyzer = array();
    private $weeks        = array();
    private $current      = '';

    public function dependsOnAnalysis() {
        return array('Analyze',);
    }

    private function loadWeekly() {
        $this->current = (new \Datetime('now'))->format('Y-W');
        for ($i = 0; $i < 5; ++$i) {
            $date = (new \Datetime('now'))->sub(new \DateInterval('P' . ($i * 7) . 'D'))->format('Y-W');
            
            $json = file_get_contents("https://www.exakat.io/weekly/week-$date.json");
            $this->weeks[$date] = json_decode($json);
            
            if (json_last_error() != '') {
                print "Error : could not read week details for $date\n";
                continue;
            }

            $analyzerListSql = makeList($this->weeks[$date]->analysis);
            $query = "SELECT analyzer, count FROM resultsCounts WHERE analyzer in ($analyzerListSql)";
            $res = $this->sqlite->query($query);
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $this->resultsCounts[$row['analyzer']] = $row['count'];
            }
        }

    // special case for 'Future read'
        $date = date('Y-W', strtotime(date('Y') . 'W' . (date('W') + 1) . '1'));
        $json = file_get_contents("https://www.exakat.io/weekly/week-$date.json");
        $this->weeks[$date] = json_decode($json);
        
        if (json_last_error() != '') {
            print "Error : could not read week details for $date\n";
        }

       $analyzerListSql = makeList($this->weeks[$date]->analysis);
       $query = "SELECT analyzer, count FROM resultsCounts WHERE analyzer in ($analyzerListSql)";
       $res = $this->sqlite->query($query);
       while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
           $this->resultsCounts[$row['analyzer']] = $row['count'];
       }

    }

    private function generateWeekly(Section $section, $year, $week) {
        $analyzerList = $this->weeks["$year-$week"]->analysis;
        $this->generateIssuesEngine($section, 
                                    $this->getIssuesFaceted($analyzerList));
    }

    private function getGrades() {
        $levels = array(
            'Critical' => 5,
            'Major'    => 4,
            'Minor'    => 3,
            'Note'     => 2,
            'None'     => 1,
        );

        $all = array_merge(...array_column($this->weeks, 'analysis'));
        foreach($all as $analyzer) {
            $severity = $this->getDocs($analyzer, 'severity');
            $this->grading[$analyzer] = $levels[$severity];
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

    protected function generateWeek0(Section $section) {
        $this->generateWeekly($section, date('Y'), date('W'));
    }

    protected function generateWeek1(Section $section) {
        $this->generateWeekly($section, date('Y'), (int) date('W') - 1);
    }

    protected function generateWeek2(Section $section) {
        $this->generateWeekly($section, date('Y'), (int) date('W') - 2);
    }

    protected function generateWeek3(Section $section) {
        $this->generateWeekly($section, date('Y'), (int) date('W') - 3);
    }

    protected function generateWeek4(Section $section) {
        $this->generateWeekly($section, date('Y'), (int) date('W') - 4);
    }

    protected function generateWeekNext(Section $section) {
        $this->generateWeekly($section, date('Y'), (int) date('W') + 1);
    }

    protected function generateDashboard(Section $section) {
        $this->loadWeekly();

        $this->getGrades();

        $baseHTML = $this->getBasedPage($section->source);

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

        // bloc by week
        $table = $this->generateWeeklyTable();
        $byweek = <<<HTML
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">This Week</h3>
                </div>
$table
                <!-- /.box-body -->
              </div>

HTML;
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCKBYWEEK', $byweek);

        // Marking the audit date
        $this->makeAuditDate($finalHTML);

        // top 10
        $week = array_keys($this->weeks)[0];
        $fileHTML     = $this->getTopFile($this->weeks[$this->current]->analysis, "weekly-$week");
        $finalHTML    = $this->injectBloc($finalHTML, 'TOPFILE', $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers($this->weeks[$this->current]->analysis, "weekly-$week");
        $finalHTML    = $this->injectBloc($finalHTML, 'TOPANALYZER', $analyzerHTML);
        
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
        foreach($globalData as $data) {
            $data->value = intval($data->value * 100) / 100;
        }
        
        $globalData = json_encode(array_values($globalData));

        $blocjs = <<<JAVASCRIPT
  <script>
    $(document).ready(function() {
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
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function getAnalyzersCount($limit) {
        $list = $this->weeks[$this->current]->analysis;
        $listSQL = makeList($list);
        $list = array_flip($list);

        $query = "SELECT analyzer, count(*) AS value
                    FROM results
                    WHERE analyzer in ($listSQL)
                    GROUP BY analyzer
                    ORDER BY count(*) DESC ";
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = $row;
            unset($list[$row['analyzer']]);
        }

        foreach(array_keys($list) as $analyzer => $value) {
            $data[] = compact('analyzer','value');
        }

        return $data;
    }

    protected function generateWeeklyTable() {
        $html = str_repeat('<div class="clearfix">
              <div class="block-cell-name">&nbsp;</div>
              <div class="block-cell-issue text-center">&nbsp;</div>
          </div>', 5);

        foreach (array_keys($this->weeks) as $id => $week) {
            $total = 0;
            foreach($this->weeks[$week]->analysis as $analyzer) {
                $total += $this->resultsCounts[$analyzer];
            }
            $html .= '<div class="clearfix">
                    <a href="weekly-' . $week . '.html">
                      <div class="block-cell-name">' . $this->titles[$id] . '</div>
                    </a>
                    <div class="block-cell-issue text-center">' . $total . '</div>
                  </div>';
        }

        $html .= str_repeat('<div class="clearfix">
              <div class="block-cell-name">&nbsp;</div>
              <div class="block-cell-issue text-center">&nbsp;</div>
          </div>', 5);

        return $html;
    }
}

?>