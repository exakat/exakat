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
use Exakat\Data\Methods;
use Exakat\Config;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Top10 extends Ambassador {
    const FILE_FILENAME  = 'top10';
    const FILE_EXTENSION = '';

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

    private $compatibilities = array();

    public function __construct($config) {
        parent::__construct($config);

        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $this->compatibilities[$shortVersion] = "Compatibility PHP $shortVersion[0].$shortVersion[1]";
        }

        if ($this->themes !== null) {
            $this->frequences        = $this->themes->getFrequences();
            $this->timesToFix        = $this->themes->getTimesToFix();
            $this->themesForAnalyzer = $this->themes->getThemesForAnalyzer();
            $this->severities        = $this->themes->getSeverities();
        }

        $this->themesToShow = array('Top10');
    }

    public function dependsOnAnalysis() {
        return array('Top10',
                     );
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/base.html");

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = <<<'MENU'
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">&nbsp;</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="top10.html"><i class="fa fa-flag"></i> <span>Top 10</span></a></li>
          <li><a href="issues.html"><i class="fa fa-flag"></i> <span>Issues</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-sticky-note-o"></i> <span>Annexes</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="annex_settings.html"><i class="fa fa-circle-o"></i>Analyzer Settings</a></li>
              <li><a href="proc_analyzers.html"><i class="fa fa-circle-o"></i>Processed Analyzers</a></li>
              <li><a href="codes.html"><i class="fa fa-circle-o"></i>Codes</a></li>
              <li><a href="analyzers_doc.html"><i class="fa fa-circle-o"></i>Documentation</a></li>
              <li><a href="credits.html"><i class="fa fa-circle-o"></i>Credits</a></li>
            </ul>
          </li>
        </ul>
        <!-- /.sidebar-menu -->
MENU;

            $compatibilities = array();
            $res = $this->sqlite->query('SELECT DISTINCT SUBSTR(thema, -2) FROM themas WHERE thema LIKE "Compatibility%" ORDER BY thema DESC');
            while($row = $res->fetchArray(\SQLITE3_NUM)) {
                $compatibilities []= "              <li><a href=\"compatibility_php$row[0].html\"><i class=\"fa fa-circle-o\"></i>{$this->compatibilities[$row[0]]}</a></li>\n";
            }

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
            $baseHTML = $this->injectBloc($baseHTML, 'COMPATIBILITIES', implode(PHP_EOL, $compatibilities));
        }

        $subPageHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/{$file}.html");
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    protected function putBasedPage($file, $html) {
        if (strpos($html, '{{BLOC-JS}}') !== false) {
            $html = str_replace('{{BLOC-JS}}', '', $html);
        }
        $html = str_replace('{{TITLE}}', 'PHP Static analysis for ' . $this->config->project, $html);

        file_put_contents($this->tmpName . '/datas/' . $file . '.html', $html);
    }

    protected function injectBloc($html, $bloc, $content) {
        return str_replace('{{' . $bloc . '}}', $content, $html);
    }

    public function generate($folder, $name = self::FILE_FILENAME) {
        if ($name == self::STDOUT) {
            print "Can't produce Diplomat format to stdout\n";
            return false;
        }

        $this->finalName = "$folder/$name";
        $this->tmpName   = "$folder/.$name";

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateDashboard();

        $analyzersList = array_merge($this->themes->getThemeAnalyzers($this->dependsOnAnalysis()));
        $analyzersList = array_unique($analyzersList);
        $this->generateDocumentation($analyzersList);
        $this->generateIssues();
        $this->generateTop10();

        // annex
        $this->generateAnalyzerSettings();
        $this->generateCodes();
        $files = array('credits');
        $this->generateAnalyzersList();

        $this->generateFiles();
        $this->generateAnalyzers();
        
        $this->cleanFolder();
    }

    protected function generateIssues() {
        $this->generateIssuesEngine('issues',
                                    $this->getIssuesFaceted('Top10') );
    }

    private function generateTop10() {
        $top10 = array('Dangling reference'      => array('Structures/DanglingArrayReference'),
                       'For with count'          => array('Structures/ForWithFunctioncall',),
                       'Next month trap'         => array('Structures/NextMonthTrap',),
                       'array_merge in loops'    => array('Performances/CsvInLoops',
                                                          'Performances/NoConcatInLoop',
                                                          'Performances/ArrayMergeInLoops',),
                       'strpos() fail'           => array('Structures/StrposCompare',
                                                          'Security/MinusOneOnError',),
                       'Shorten first'           => array('Performances/SubstrFirst',),
                       'Don\'t unset properties' => array('Classes/DontUnsetProperties',),
                       'Operators precedence'    => array('Php/LogicalInLetters',
                                                          'Php/ConcatAndAddition',
                                                         ),
                       'Missing subpattern'      => array('Php/MissingSubpattern',),
                       'Avoir real'              => array('Php/AvoidReal',
                                                          'Type/NoRealComparison',),
                     );

        $sqlList = makeList(array_merge(...array_values($top10)));

        $sql = <<<SQL
SELECT * FROM resultsCounts
    WHERE analyzer IN ($sqlList)
SQL;
        $res = $this->sqlite->query($sql);

        $counts = array_fill_keys(array_keys($top10), 0);
        $dict = array();
        foreach($top10 as $t => $v) {
            foreach($v as $w) {
                $dict[$w] = $t;
            }
        }

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$dict[$row['analyzer']]] += $row['count'];
        }

            $colors = array('#00FF00',
                            '#32CC00',
                            '#669900',
                            '#996600',
                            '#CC3300',
                            '#FF0000',
                            );

        $table = array();
        $i = 0;
        foreach($counts as $name => $count) {
            ++$i;
            $color = $colors[round(log($count) / log(5), 0)];
            $table[] = "<tr><td>$name</td><td><a href=\"issues.html#analyzer=" . $this->toId($row['analyzer']) . '" title="' . $row['label'] . '">' . $row['label'] . "</a></td><td bgcolor=\"$color\">$count</td></tr>\n";
        }

        $top10 = '<table class="table">' . implode(PHP_EOL, $table) . '</table>';

        $description = <<<'HTML'
<i class="fa fa-check-square-o"></i> : Nothing found for this analysis, proceed with caution; <i class="fa fa-warning red"></i> : some issues found, check this; <i class="fa fa-ban"></i> : Can't test this, PHP version incompatible; <i class="fa fa-cogs"></i> : Can't test this, PHP configuration incompatible; 
HTML;

        $html = $this->getBasedPage('compatibility');
        $html = $this->injectBloc($html, 'COMPATIBILITY', $top10);
        $html = $this->injectBloc($html, 'TITLE', 'Top 10 classic errors ');
        $html = $this->injectBloc($html, 'DESCRIPTION', '');
        $this->putBasedPage('top10', $html);

    }
}

?>