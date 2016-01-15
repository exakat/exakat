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

        $css = file_get_contents($config->dir_root.'/media/faceted/faceted.css');
        file_put_contents($dirName.'/'.$fileName.'/faceted.css', $css);        

        $html = file_get_contents($config->dir_root.'/media/faceted/index.html');

        $html = str_replace('PROJECT_NAME', $this->config->project_name, $html);

//        $html = str_replace('EXAKAT_VERSION', \Exakat::VERSION, $html);
//        $html = str_replace('EXAKAT_BUILD', \Exakat::BUILD, $html);
//        $html = str_replace('PROJECT_FAVICON', $faviconHtml, $html);

        file_put_contents($dirName.'/'.$fileName.'/index.html', $html);        

        $sqlite      = new \sqlite3($dirName.'/dump.sqlite');

        $errors = json_decode($json);
        $docsList = array();
        foreach($errors as $error) {
            $docsList[$error->analyzer] = $error->error;
        }
        asort($docsList);

        $docsHtml = '<dl>';
        foreach($docsList as $id => $dl) {
            $ini = parse_ini_file($config->dir_root.'/human/en/'.$id.'.ini');
            $description = htmlentities($ini['description'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
            $description = preg_replace_callback('/\s*(&lt;\?php.*?\?&gt;)\s*/si', function ($r) { return '<br />'.highlight_string(html_entity_decode($r[1]), true);}, $description);
            $description = nl2br($description);

            $docsHtml .= "<dt id=\"$id\">$dl</dt>
        <dd>$description</dd>\n";
        }
        $docsHtml .= '</dl>';

        $docs = file_get_contents($config->dir_root.'/media/faceted/docs.html');
        $docs = str_replace('DOCS_LIST', $docsHtml, $docs);
        $docs = str_replace('PROJECT_NAME', $this->config->project_name, $docs);
        file_put_contents($dirName.'/'.$fileName.'/docs.html', $docs);        
        
    }
}