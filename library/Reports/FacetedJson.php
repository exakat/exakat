<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Reports;

class FacetedJson extends Reports {
    const FILE_EXTENSION = 'json';

    public function generateFileReport($report) {}

    public function generate($dirName, $fileName) {
        $this->themes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('Dead Code'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('Security'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP53'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP70'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP71')
                                    );
        $themesList = '("'.join('", "', $this->themes).'")';

        $sqlite      = new \sqlite3($dirName.'/dump.sqlite');

        $sqlQuery = <<<SQL
SELECT  id AS id,
        fullcode AS code, 
        file AS file, 
        line AS line,
        analyzer AS error
    FROM results 
    WHERE analyzer IN $themesList

SQL;
        $res = $sqlite->query($sqlQuery);
        
        $items = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $items[] = $row;
            $this->count();
        }

        file_put_contents($dirName.'/'.$fileName.'.'.self::FILE_EXTENSION, json_encode($items));
        
    }//end generate()


}//end class