<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Faceted extends FacetedJson {
    const FOLDER_PRIVILEGES = 0755;

    public function generateFileReport($report) {}

    public function generate($dirName, $fileName = null) {
        if ($fileName === null) {
            return "Can't produce report to stdout\n";
        }

        $sqlite      = new \sqlite3($dirName.'/dump.sqlite');
        $config = \Config::factory();

        if ($dirName.'/'.$fileName !== '/') {
            shell_exec('rm -rf '.$dirName.'/'.$fileName);
        }

        mkdir($dirName.'/'.$fileName, Faceted::FOLDER_PRIVILEGES);

        $json = parent::generate($dirName);
        $js = file_get_contents($config->dir_root.'/media/faceted/app.js');
        $js = str_replace('DUMP_JSON', $json, $js);
        file_put_contents($dirName.'/'.$fileName.'/app.js', $js);        

        $html = file_get_contents($config->dir_root.'/media/faceted/index.html');

        $html = str_replace('PROJECT_NAME', $this->config->project_name, $html);

//        $html = str_replace('EXAKAT_VERSION', \Exakat::VERSION, $html);
//        $html = str_replace('EXAKAT_BUILD', \Exakat::BUILD, $html);
//        $html = str_replace('PROJECT_FAVICON', $faviconHtml, $html);

        file_put_contents($dirName.'/'.$fileName.'/index.html', $html);        
        
    }
}