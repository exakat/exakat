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

        $sqlite      = new \sqlite3($dirName.'/dump.sqlite', SQLITE3_OPEN_READONLY);
        $config = \Config::factory();

        // Clean final destination
        if ($dirName.'/'.$fileName !== '/') {
            rmdirRecursive($dirName.'/'.$fileName);
        }

        if (file_exists($dirName.'/'.$fileName)) {
            display ($dirName.'/'.$fileName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        // Clean temporary destination
        if (file_exists($dirName.'/'.$fileName)) {
            rmdirRecursive($dirName.'/'.$fileName);
        }
        
        $finalName = $fileName;
        $fileName = '.'.$fileName;
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

        $errors = json_decode($json);
        $docsList = array();
        $filesList = array();
        foreach($errors as $error) {
            $docsList[$error->analyzer] = $error->error;
            $filesList[$error->file] = $error->line;
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
        
        foreach($filesList as $path => $line) {
            $dirs = explode('/', $path);
            array_pop($dirs); // remove file name
            array_shift($dirs); // remove root /
            $d = '';
            foreach($dirs as $dir) {
                $d .= '/'.$dir;
                if (!file_exists($dirName.'/'.$fileName.$d)) {
                    mkdir($dirName.'/'.$fileName.$d, 0755);
                }
            }

            $php = file_get_contents($dirName.'/code'.$path);
            $html = highlight_string($php, true);
            $html = preg_replace_callback('$<br />$s', function ($r) { static $line; if (!isset($line)) { $line = 2; } else { ++$line; } return "<br id=\"$line\" />$line) ";}, $html);
            $html = '<code><a id="1" />1) '.substr($html, 6);
            file_put_contents($dirName.'/'.$fileName.$path.'.html', $html);
        }
        
        rename($dirName.'/'.$fileName, $dirName.'/'.$finalName);

    }
}