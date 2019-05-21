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
use Exakat\Reports\Helpers\Results;
use Symfony\Component\Yaml\Yaml;

class Exakatyaml extends Reports {
    const FILE_EXTENSION = 'yml';
    const FILE_FILENAME  = '.exakat';

    public function _generate($analyzerList) {
        $return = array('project'         => $this->config->project,
                        'project_name'    => $this->config->project_name,
                        'project_themes'  => $this->config->project_themes,
                        'project_reports' => $this->config->project_reports,
                        'rulesets'        => range(0, 10),
        );
        
        $rules = array();
        $sqlList = makeList($analyzerList);
        $res = $this->sqlite->query('SELECT * FROM resultsCounts WHERE analyzer IN ('.$sqlList.') AND count >= 0');
        while($row = $res->fetcharray(\SQLITE3_ASSOC)) {
            if (isset($rules[$row['count']])) {
                $rules[$row['count']][] = $row['analyzer'];
            } else {
                $rules[$row['count']] = array($row['analyzer']);
            }
        }

        ksort($rules);
        $return['rulesets'] = $rules;

        $yaml = Yaml::dump($return);

        $yaml = preg_replace_callback('/    (\d+): \[(.+?)\]/m', array($this, 'format'), $yaml);

        return $yaml;
    }
    
    private function format($r) {
        $number_size = strlen($r[1]) + 2;
        $ident = str_repeat(' ', 8);
        
        $list = explode(', ', $r[2]);
    
        foreach($list as &$l) {
            $title = $this->getDocs($l, 'name');
            $pad = str_repeat(' ', 50 - strlen($title));
            $l     = " {$ident}\"$title\":$pad$l";
        }
        
        sort($list);
        $list = implode("\n", $list);
        return <<<YAML
    ruleset_$r[1]: # $r[1] errors found
$list
YAML;
    }
}

?>