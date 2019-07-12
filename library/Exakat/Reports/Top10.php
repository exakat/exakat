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
    const CONFIG_YAML    = 'top10';

    protected $frequences        = array();
    protected $timesToFix        = array();
    protected $themesForAnalyzer = array();
    protected $severities        = array();

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
            $this->themesForAnalyzer = $this->themes->getRulesetsForAnalyzer();
            $this->severities        = $this->themes->getSeverities();
        }

        $this->themesToShow = array('Top10');
    }

    public function dependsOnAnalysis() {
        return array('Top10',
                     );
    }

    protected function generateIssues() {
        $this->generateIssuesEngine('issues',
                                    $this->getIssuesFaceted('Top10') );
    }

    protected function generateTop10(Section $section) {
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
                       'Avoid real'              => array('Php/AvoidReal',
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

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPATIBILITY', $top10);
        $html = $this->injectBloc($html, 'TITLE', 'Top 10 classic errors ');
        $html = $this->injectBloc($html, 'DESCRIPTION', $description);
        $this->putBasedPage($section->file, $html);

    }
}

?>