<?php declare(strict_types = 1);
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
use Exakat\Reports\Helpers\Highchart;

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
    private $globalGrade  = 0;
    private $rulesToShow = array();

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
    private $resultsCounts = array();

    private $weeks        = array();
    private $current      = '';

    public function dependsOnAnalysis(): array {
        return array('Analyze',
                    );
    }

    private function loadWeekly() {
        $this->current = (new \Datetime('now'))->format('Y-W');
        for ($i = 0; $i < 5; ++$i) {
            $date = (new \Datetime('now'))->sub(new \DateInterval('P' . ($i * 7) . 'D'))->format('Y-W');

            $json = file_get_contents("https://exakat.io/weekly/week-$date.json") ?: '';
            $this->weeks[$date] = json_decode($json);

            if (json_last_error() != '') {
                print "Error : could not read week details for $date\n";
                continue;
            }

            $res = $this->dump->fetchAnalysersCounts($this->weeks[$date]->analysis);
            foreach($res->toArray() as $row) {
                $this->resultsCounts[$row['analyzer']] = $row['count'];
            }
        }

    // special case for 'Future read'
        $date = date('Y-W', strtotime(date('Y') . 'W' . substr('0' . ((int) date('W') + 1), -2)));
        $json = file_get_contents("https://www.exakat.io/weekly/week-$date.json") ?: '';
        $this->weeks[$date] = json_decode($json);

        if (json_last_error() != '') {
            print "Error : could not read week details for $date\n";
        }

        $res = $this->dump->fetchAnalysersCounts($this->weeks[$date]->analysis ?? array());
        foreach($res->toArray() as $row) {
            $this->resultsCounts[$row['analyzer']] = $row['count'];
        }
    }

    private function generateWeekly(Section $section, int $year, int $week): void {
        $analyzerList = $this->weeks["$year-" . substr("0$week", -2)]->analysis ?? array();
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
            $severity = $this->docs->getDocs($analyzer, 'severity');
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

    protected function generateWeek0(Section $section): void {
        $this->generateWeekly($section, (int) date('Y'), (int) date('W'));
    }

    protected function generateWeek1(Section $section): void {
        if ((int) date('W') - 1 > 0) {
            $this->generateWeekly($section, (int) date('Y'), (int) date('W') - 1);
        } else {
            $this->generateWeekly($section, (int) date('Y') - 1, 53 - (int) date('W'));
        }
    }

    protected function generateWeek2(Section $section): void {
        if ((int) date('W') - 1 > 0) {
            $this->generateWeekly($section, (int) date('Y'), (int) date('W') - 2);
        } else {
            $this->generateWeekly($section, (int) date('Y') - 1, 52 - (int) date('W'));
        }
    }

    protected function generateWeek3(Section $section): void {
        if ((int) date('W') - 1 > 0) {
            $this->generateWeekly($section, (int) date('Y'), (int) date('W') - 3);
        } else {
            $this->generateWeekly($section, (int) date('Y') - 1, 51 - (int) date('W'));
        }
    }

    protected function generateWeek4(Section $section): void {
        if ((int) date('W') - 1 > 0) {
            $this->generateWeekly($section, (int) date('Y'), (int) date('W') - 4);
        } else {
            $this->generateWeekly($section, (int) date('Y') - 1, 50 - (int) date('W'));
        }
    }

    protected function generateWeekNext(Section $section): void {
        $this->generateWeekly($section, (int) date('Y'), (int) date('W') + 1);
    }

    protected function generateDashboard(Section $section): void {
        $this->loadWeekly();

        $this->getGrades();

        $baseHTML = $this->getBasedPage($section->source);

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
$table[html]
                <!-- /.box-body -->
              </div>

HTML;
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCKBYWEEK', $byweek);

        // Marking the audit date
        $this->makeAuditDate($finalHTML);

        // top 10
        $week = array_keys($this->weeks)[0];
        $fileHTML     = $this->getTopFile($this->weeks[$this->current]->analysis ?? array(), "week0");
        $finalHTML    = $this->injectBloc($finalHTML, 'TOPFILE', $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers($this->weeks[$this->current]->analysis ?? array(), "week0");
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
        $donut = array();
        foreach($globalData as $data) {
            $donut[] = array('label' => $data->label,
                             'value' => intval($data->value * 100) / 100,
                             );
        }

        $severity = $this->getSeverityBreakdown();

        $highchart = new Highchart();

        $highchart->addDonut('donut-chart_grade',  $donut);

        $this->rulesToShow = $this->weeks[array_keys($this->weeks)[0]]->analysis;
        $fileOverview = $this->getFileOverview();
        $highchart->addSeries('filename',
                              $fileOverview['scriptDataFiles'],
                              array('name' => 'Critical', 'data' => $fileOverview['scriptDataCritical']),
                              array('name' => 'Major',    'data' => $fileOverview['scriptDataMajor']),
                              array('name' => 'Minor',    'data' => $fileOverview['scriptDataMinor']),
                              array('name' => 'None',     'data' => $fileOverview['scriptDataNone'])
                              );

        $analyzerOverview = $this->getAnalyzerOverview();
        $highchart->addSeries('container',
                              $analyzerOverview['scriptDataAnalyzer'],
                              array('name' => 'Critical', 'data' => $analyzerOverview['scriptDataAnalyzerCritical']),
                              array('name' => 'Major',    'data' => $analyzerOverview['scriptDataAnalyzerMajor']),
                              array('name' => 'Minor',    'data' => $analyzerOverview['scriptDataAnalyzerMinor']),
                              array('name' => 'None',     'data' => $analyzerOverview['scriptDataAnalyzerNone'])
                              );

        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function getAnalyzersCount(int $limit): array {
        $res = $this->dump->getAnalyzersCount($this->weeks[$this->current]->analysis ?? array());

        return array_slice($res->toArray(), 0, $limit);
    }

    protected function generateWeeklyTable() {
        $html = str_repeat('<div class="clearfix">
              <div class="block-cell-name">&nbsp;</div>
              <div class="block-cell-issue text-center">&nbsp;</div>
          </div>', 5);

        $dataScript = array();
        foreach (array_keys($this->weeks) as $id => $week) {
            $total = 0;
            foreach($this->weeks[$week]->analysis as $analyzer) {
                $total += $this->resultsCounts[$analyzer] ?? 0;
                $dataScript[] = $this->resultsCounts[$analyzer];
            }

            $html .= <<<HTML
    <div class="clearfix">
      <a href="week$id.html">
        <div class="block-cell-name">{$this->titles[$id]}</div>
      </a>
      <div class="block-cell-issue text-center">$total</div>
    </div>
HTML;
        }

        $html .= str_repeat('<div class="clearfix">
              <div class="block-cell-name">&nbsp;</div>
              <div class="block-cell-issue text-center">&nbsp;</div>
          </div>', 5);

        return array('html'   => $html,
                     'script' => $dataScript);
    }

    protected function getSeveritiesNumberBy(string $type = 'file'): array {
        $res = $this->dump->getSeveritiesNumberBy($this->rulesToShow, $type);

        $return = array();
        foreach($res->toArray() as $value) {
            $return[$value[$type]][$value['severity']] = $value['count'];
        }

        return $return;
    }
}

?>