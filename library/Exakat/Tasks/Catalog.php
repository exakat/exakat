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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Reports\Reports;
use Exakat\Analyzer\Themes;

class Catalog extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $data = array();

        // List of analysis
        $themas = $this->themes->listAllThemes();
        sort($themas);
        $themas = array_map( function ($x) {
            if (strpos($x, ' ') !== false) {
                $x = '"'.$x.'"';
            }
            return $x;
        }, $themas);
        $data['analysis'] = $themas;

        // List of reports
        $reports = Reports::$FORMATS;
        sort($reports);
        $data['reports'] = $reports;

        if ($this->config->json === true) {
            print json_encode($data);
        } else {
            $display = '';

            foreach($data as $theme => $list) {
                $display .= count($list)." $theme : \n";
                $display .= "   ".implode("\n   ", $list)."\n";
            }

            print $display;
        }
    }
}

?>
