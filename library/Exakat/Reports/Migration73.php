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
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Migration73 extends Ambassador {
    const FILE_FILENAME  = 'migration73';
    const FILE_EXTENSION = '';

    public function dependsOnAnalysis() {
        return array('CompatibilityPHP73',
                     );
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/base.html");
            $title = ($file == 'index') ? 'Dashboard' : $file;
            $project_name = $this->config->project_name;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $project_name);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_NAME', $project_name);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($project_name{0}));

            $menu = file_get_contents("{$this->tmpName}/datas/menuMigration73.html");
            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
        }

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    public function generate($folder, $name = self::FILE_FILENAME) {
        if ($name === self::STDOUT) {
            print "Can't produce Migration73 format to stdout\n";
            return false;
        }
        
        if ($missing = $this->checkMissingThemes()) {
            print "Can't produce Migration73 format. There are ".count($missing)." missing themes : ".implode(', ', $missing).".\n";
            return false;
        }

        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->projectPath = $folder;

        $this->initFolder();

        $this->generateSettings();
        $this->generateSuggestions();

        $this->generateDashboard();

        // Compatibility
        $this->generateCompilations();
        $res = $this->sqlite->query('SELECT DISTINCT SUBSTR(thema, -2) AS version FROM themas WHERE thema LIKE "CompatibilityPHP73"');
        $list = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $list[] = "CompatibilityPHP$row[version]";
            $this->generateCompatibility($row['version']);
        }
        $this->generateCompatibilityEstimate();
        $this->generateIssuesEngine('compatibility_issues',
                                    $this->getIssuesFaceted($list));

        // Annex
        $this->generateAnalyzerSettings();
        $analyzersList = array_merge($this->themes->getThemeAnalyzers($this->dependsOnAnalysis()));
        $analyzersList = array_unique($analyzersList);
        $this->generateDocumentation($analyzersList);
        $this->generateCodes();

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    public function getFilesCount($limit = null) {
        $list = $this->themes->getThemeAnalyzers('CompatibilityPHP73');
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

    protected function getAnalyzersCount($limit) {
        $list = $this->themes->getThemeAnalyzers('CompatibilityPHP73');
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
}

?>