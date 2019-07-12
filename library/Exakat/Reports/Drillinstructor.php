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
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Drillinstructor extends Ambassador {
    const FILE_FILENAME  = 'drill';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Drillinstructor';
/*
    public function generate($folder, $name = self::FILE_FILENAME) {
        if ($name === self::STDOUT) {
            print "Can't produce DrillInstructor format to stdout\n";
            return false;
        }
        
        $this->finalName = "$folder/$name";
        $this->tmpName   = "{$this->config->tmp_dir}/.$name";

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateDashboard();
        $this->generateLevels();
        $this->generateLevel1();
        $this->generateLevel2();
        $this->generateLevel3();
        $this->generateLevel4();
//        $this->generateLevel5();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateDocumentation($this->themes->getRulesetsAnalyzers($this->themesToShow));

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

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
          <li><a href="levels.html"><i class="fa fa-dashboard"></i>Overview of levels</a></li>
          <li><a href="level1.html"><i class="fa fa-circle-o"></i>Level 1</a></li>
          <li><a href="level2.html"><i class="fa fa-circle-o"></i>Level 2</a></li>
          <li><a href="level3.html"><i class="fa fa-circle-o"></i>Level 3</a></li>
          <li><a href="level3.html"><i class="fa fa-circle-o"></i>Level 4</a></li>
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

        $subPageHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/' . $file . '.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

*/
    protected function generateLevel(Section $section) {
        $this->generateIssuesEngine($section,
                                    $this->getIssuesFaceted('Level 1'));
    }

    protected function generateLevels(Section $section) {
        $levels = '';
        
        foreach(range(1, 6) as $level) {
            $levelRows = '';
            $total = 0;
            $analyzers = $this->themes->getRulesetsAnalyzers(array('Level ' . $level));
            if (empty($analyzers)) {
                continue;
            }
            $analyzersList = makeList($analyzers);
        
            $res = $this->sqlite->query(<<<SQL
SELECT analyzer AS name, count FROM resultsCounts WHERE analyzer in ($analyzersList) AND count >= 0 ORDER BY count
SQL
);
            $colors = array('A' => '#00FF00',
                            'B' => '#32CC00',
                            'C' => '#669900',
                            'D' => '#996600',
                            'E' => '#CC3300',
                            'F' => '#FF0000',
                            );
            $count = 0;
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $ini = $this->getDocs($row['name']);

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = min(ceil(log($row['count']) / log(count($colors))), count($colors) - 1);
                    $row['grade'] = chr(66 + $grade); // B to F
                }
                $row['color'] = $colors[$row['grade']];
                
                $total += $row['count'];
                $count += (int) $row['count'] === 0;
    
                $levelRows .= '<tr><td>' . $ini['name'] . "</td><td>$row[count]</td><td style=\"background-color: $row[color]\">$row[grade]</td></tr>\n";
            }

            if (count($analyzers) === 1) {
                $grade = 'A';
            } else {
                $grade = floor($count / (count($analyzers) - 1) * (count($colors) - 1));
                $grade = chr(65 + $grade); // B to F
            }
            $color = $colors[$grade];
            
            $levels .= '<tr><td style="background-color: #bbbbbb">Level ' . $level . '</td>
                            <td style="background-color: #bbbbbb">' . $total . '</td></td>
                            <td style="background-color: ' . $color . '">' . $grade . '</td></tr>' . PHP_EOL .
                       $levelRows;
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }
}

?>