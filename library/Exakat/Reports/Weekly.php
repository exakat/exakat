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

    private $timesToFix        = null;
    private $severities        = null;

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
    private $resultsCounts = null;
    
    private $usedAnalyzer = array();
    private $weeks        = array();
    private $current      = '';

    public function __construct($config) {
        parent::__construct($config);
        $this->timesToFix        = $this->themes->getTimesToFix();
        $this->severities        = $this->themes->getSeverities();
        
        $this->current =  date('Y-W', strtotime(date('Y')."W".(date('W'))."1"));
        for ($i = 0; $i < 5; ++$i) {
            $date = date('Y-W', strtotime(date('Y')."W".(date('W') - $i)."1"));
            $json = file_get_contents("https://www.exakat.io/weekly/week-$date.json");
            $this->weeks[$date] = json_decode($json);
            
            if (json_last_error() != '') {
                print "Error : could not read week details for $date\n";
            }
        }

    // special case for 'Future read'
        $date = date('Y-W', strtotime(date('Y')."W".(date('W') + 1)."1"));
        $json = file_get_contents("https://www.exakat.io/weekly/week-$date.json");
        $this->weeks[$date] = json_decode($json);
        
        if (json_last_error() != '') {
            print "Error : could not read week details for $date\n";
        }
        
        $all = array_merge(...array_column($this->weeks, 'analysis'));
        $this->results = new Results($this->sqlite, $all);
        $this->results->load();

        $this->resultsCounts = array_fill_keys($all, 0);
        foreach($this->results->toArray() as $result) {
            ++$this->resultsCounts[$result['analyzer']];
        }
        
        $levels = array(
            'Critical' => 5,
            'Major'    => 4,
            'Minor'    => 3,
            'Note'     => 2,
            'None'     => 1,
        );

        foreach($all as $analyzer) {
            $severity = $this->getDocs($analyzer, 'severity');
            $this->grading[$analyzer] = $levels[$severity];
        }
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_NAME', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            // Moving the first in the last position
            $weeks = array_keys($this->weeks);
            $weeksMenu = array();
            foreach($weeks as $id => $week) {
                $title = $this->titles[$id];
                $weeksMenu[] = "          <li><a href=\"weekly-$week.html\"><i class=\"fa fa-flag\"></i> <span>$title</span></a></li>";
            }
            $weeksMenu = implode(PHP_EOL, $weeksMenu);

            $menu = <<<MENU
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">&nbsp;</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          $weeksMenu
          <li class="treeview">
            <a href="#"><i class="fa fa-sticky-note-o"></i> <span>Annexes</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="annex_settings.html"><i class="fa fa-circle-o"></i>Analyzer Settings</a></li>
              <li><a href="analyzers_doc.html"><i class="fa fa-circle-o"></i>Analyzers Documentation</a></li>
              <li><a href="codes.html"><i class="fa fa-circle-o"></i>Codes</a></li>
              <li><a href="credits.html"><i class="fa fa-circle-o"></i>Credits</a></li>
            </ul>
          </li>
        </ul>
        <!-- /.sidebar-menu -->
MENU;

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
        }

        $subPageHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/{$file}.html");
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    public function generate($folder, $name = 'report') {
        if ($name === self::STDOUT) {
            print "Can't produce Grade format to stdout\n";
            return false;
        }
        
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->projectPath = $folder;

        $this->initFolder();

        $this->generateWeekly(date('Y'), date('W'));
        $this->generateWeekly(date('Y'), (int) date('W') - 1);
        $this->generateWeekly(date('Y'), (int) date('W') - 2);
        $this->generateWeekly(date('Y'), (int) date('W') - 3);
        $this->generateWeekly(date('Y'), (int) date('W') - 4);
        $this->generateWeekly(date('Y'), (int) date('W') + 1);
        $this->generateDashboard();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateDocumentation($this->usedAnalyzer);
        $this->generateCodes();

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    protected function cleanFolder() {
        if (file_exists("{$this->tmpName}/datas/base.html")) {
            unlink("{$this->tmpName}/datas/base.html");
            unlink("{$this->tmpName}/datas/menu.html");
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

    private function generateWeekly($year, $week) {
        if (empty($this->weeks["$year-$week"])) {
            return;
        }

        $analyzerList = $this->weeks["$year-$week"]->analysis;
        $this->generateIssuesEngine("weekly",
                                    $this->getIssuesFaceted($analyzerList));

        $analyzerListSql = makeList($analyzerList);
        $query = "SELECT analyzer, count FROM resultsCounts WHERE analyzer in ($analyzerListSql)";
        $res = $this->sqlite->query($query);
        $counts = array();
        $total_issues = 0;
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['count'];
            $total_issues += $row['count'];
        }

        $html = file_get_contents("$this->tmpName/datas/weekly.html");

        $docs = array();
        foreach($this->weeks["$year-$week"]->analysis as $analyzer) {
            $ini = $this->getDocs($analyzer);
            $item['analyzer']       = $ini['name'];
            $docId   = $this->toId($analyzer);
            $docs[$analyzer] = "<a href=\"analyzers_doc.html#$docId\">$ini[name]</a>";
        }
        
        $this->usedAnalyzer = array_merge($this->usedAnalyzer, $this->weeks["$year-$week"]->analysis);

        $begin = date('M jS, Y', strtotime($year."W".$week."1"));
        $end   = date('M jS, Y', strtotime($year."W".$week."7"));
        $titleDate = $year.' '.ordinal($week)." week";

        $finalHTML = str_replace('<WEEK>', $titleDate, $html);
        $fullweek = array("From $begin to $end : <br /> Total : $total_issues <br />");
        foreach($docs as $analyzer => $doc) {
            $fullweek[] = " $doc ({$counts[$analyzer]}) ";
        }
        $finalHTML = str_replace('<FULLWEEK>', implode(' - ', $fullweek), $finalHTML).' - ';
        
        file_put_contents("{$this->tmpName}/datas/weekly.html", $finalHTML);

        copy("{$this->tmpName}/datas/weekly.html", "{$this->tmpName}/datas/weekly-$year-$week.html");

        return true;
    }

    private function getGrades() {
        $this->globalGrade = 0;
        
        $grade = 0;
        foreach($this->resultsCounts as $name => $value) {
            if ($value > 0) {
                $grade += min(log($value * $this->grading[$name]) / log(10), 5);
            }
        }
        $this->globalGrade = intval(100 * max(0, 20 - $grade)) / 100;
    }

    protected function generateDashboard() {
        $this->getGrades();

        $baseHTML = $this->getBasedPage('index_weekly');

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
        
        $globalData = array(self::G_CRITICAL  => (object) ['label' => 'Critical', 'value' => 0],
                            self::G_ERROR     => (object) ['label' => 'Error',    'value' => 0],
                            self::G_WARNING   => (object) ['label' => 'Warning',  'value' => 0],
                            self::G_NOTICE    => (object) ['label' => 'Notice',   'value' => 0],
                            self::G_NONE      => (object) ['label' => 'OK',       'value' => 0]);
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
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Grading code');
        $this->putBasedPage('index', $finalHTML);
    }

    protected function getAnalyzersCount($limit) {
        $list = $this->weeks[$this->current]->analysis;
        $listSQL = makeList($list);
        $list = array_flip($list);

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($listSQL)
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
            unset($list[$row['analyzer']]);
        }
        foreach($list as $analyzer => $foo) {
            $data[] = array('analyzer' => $analyzer,
                            'value'    => 0);
        }

        return $data;
    }

    protected function getSeveritiesNumberBy($type = 'file') {
        $list = $this->weeks[$this->current]->analysis;
        $listSQL = makeList($list);
        $list = array_flip($list);

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($listSQL)
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
    
    protected function generateWeeklyTable() {
        $data = $this->getFilesCount($this->weeks[$this->current]->analysis, self::TOPLIMIT);

        $html = '';
        $html .= str_repeat('<div class="clearfix">
              <div class="block-cell-name">&nbsp;</div>
              <div class="block-cell-issue text-center">&nbsp;</div>
          </div>', 5);

        foreach (array_keys($this->weeks) as $id => $week) {
            $total = 0;
            foreach($this->weeks[$week]->analysis as $analyzer) {
                $total += $this->resultsCounts[$analyzer];
            }
            $html .= '<div class="clearfix">
                    <a href="weekly-'.$week.'.html">
                      <div class="block-cell-name">'.$this->titles[$id].'</div>
                    </a>
                    <div class="block-cell-issue text-center">'.$total.'</div>
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