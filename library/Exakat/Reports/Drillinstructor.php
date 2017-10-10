<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Reports\Reports;

class Drillinstructor extends Ambassador {
    const FILE_FILENAME  = 'drill';

    public function __construct($config) {
        parent::__construct($config);
    }

    public function generate($folder, $name = 'report') {
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateDashboard();
        $this->generateLevel1();
        $this->generateLevel2();
        $this->generateLevel3();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateDocumentation();

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
            $baseHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = <<<MENU
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">&nbsp;</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="level1.html"><i class="fa fa-circle-o"></i>Level 1</a></li>
          <li><a href="level2.html"><i class="fa fa-circle-o"></i>Level 2</a></li>
          <li><a href="level3.html"><i class="fa fa-circle-o"></i>Level 3</a></li>
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

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }


    private function generateLevel1() {
        $this->generateIssuesEngine('level1',
                                    $this->getIssuesFaceted('Level 1'));
    }

    private function generateLevel2() {
        $this->generateIssuesEngine('level2',
                                    $this->getIssuesFaceted('Level 2'));
    }

    private function generateLevel3() {
        $this->generateIssuesEngine('level3',
                                    $this->getIssuesFaceted('Level 3'));
    }

    private function generateDocumentation(){
        $datas = array();
        $baseHTML = $this->getBasedPage('analyzers_doc');
        $analyzersDocHTML = "";

        $analyzersList = array_merge(Analyzer::getThemeAnalyzers('Level 1')
                                     );
        $analyzersList = array_keys(array_count_values($analyzersList));
                                     
        foreach($analyzersList as $analyzerName) {
            $analyzer = Analyzer::getInstance($analyzerName, null, $this->config);
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

}

?>