<?php
/*
 * Copyright 2012-2016 Damien Seguy  Exakat Ltd <contact(at)exakat.io>
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

class Faceted2 extends FacetedJson {
    const FOLDER_PRIVILEGES = 0755;

    public function generate($dirName, $fileName = null) {
        if ($fileName === null) {
            return "Can't produce report to stdout\nAborting\n";
        }

        // Clean temporary destination
        if (file_exists($dirName.'/'.$fileName)) {
            rmdirRecursive($dirName.'/'.$fileName);
        }

        $finalName = $fileName;
        $tmpFileName = '.'.$fileName;

        mkdir($dirName.'/'.$tmpFileName, self::FOLDER_PRIVILEGES);

        // Building index.html
        $html = file_get_contents($this->config->dir_root.'/media/faceted2/index.html');

        $html = str_replace('PROJECT_NAME', $this->config->project_name, $html);

        file_put_contents($dirName.'/'.$tmpFileName.'/index.html', $html);

        // Building app.js
        $js = file_get_contents($this->config->dir_root.'/media/faceted2/app.js');

        $json = parent::generate($dirName);
        $js = str_replace('DUMP_JSON', $json, $js);

        $docs = array();
        $analyzes = json_decode($json);
        foreach($analyzes as $analyze) {
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$analyze->analyzer.'.ini');
            $docs[$ini['name']] = $ini['description'];
        }
        $docs = json_encode($docs);

        $js = str_replace('__DOCS__', $docs, $js);
        $json = parent::generate($dirName);
        $js = str_replace('DUMP_JSON', $json, $js);
        print file_put_contents($dirName.'/'.$tmpFileName.'/app.js', $js).' octets écrits';

        copyDir($this->config->dir_root.'/media/faceted2/bower_components', $dirName.'/'.$tmpFileName.'/bower_components');
        copyDir($this->config->dir_root.'/media/faceted2/node_modules', $dirName.'/'.$tmpFileName.'/node_modules');
        copy($this->config->dir_root.'/media/faceted2/exakat.css', $dirName.'/'.$tmpFileName.'/exakat.css');

        $css = file_get_contents($this->config->dir_root.'/media/faceted/faceted.css');
        file_put_contents($dirName.'/'.$tmpFileName.'/faceted.css', $css);

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
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$id.'.ini');
            $description = htmlentities($ini['description'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
            $description = preg_replace_callback('/\s*(&lt;\?php.*?\?&gt;)\s*/si', function ($r) { return '<br />'.highlight_string(html_entity_decode($r[1]), true);}, $description);
            $description = nl2br($description);

            $docsHtml .= "<dt id=\"$id\">$dl</dt>
        <dd>$description</dd>\n";
        }
        $docsHtml .= '</dl>';

        $docs = file_get_contents($this->config->dir_root.'/media/faceted/docs.html');
        $docs = str_replace('DOCS_LIST', $docsHtml, $docs);
        $docs = str_replace('PROJECT_NAME', $this->config->project_name, $docs);
        file_put_contents($dirName.'/'.$tmpFileName.'/docs.html', $docs);

        foreach($filesList as $path => $line) {
            $dirs = explode('/', $path);
            array_pop($dirs); // remove file name
            array_shift($dirs); // remove root /
            $d = '';
            foreach($dirs as $dir) {
                $d .= '/'.$dir;
                if (!file_exists($dirName.'/'.$tmpFileName.$d)) {
                    mkdir($dirName.'/'.$tmpFileName.$d, 0755);
                }
            }

            $php = file_get_contents($dirName.'/code'.$path);
            $html = highlight_string($php, true);
            $html = preg_replace_callback('$<br />$s', function ($r) { static $line; if (!isset($line)) { $line = 2; } else { ++$line; } return "<br id=\"$line\" />$line) ";}, $html);
            $html = '<code><a id="1" />1) '.substr($html, 6);
            file_put_contents($dirName.'/'.$tmpFileName.$path.'.html', $html);
        }

        if (file_exists($dirName.'/'.$finalName)) {
            rename($dirName.'/'.$finalName, $dirName.'/.'.$tmpFileName);
            rename($dirName.'/'.$tmpFileName, $dirName.'/'.$finalName);

            // Clean previous folder
            if ($dirName.'/.'.$tmpFileName !== '/') {
                rmdirRecursive($dirName.'/.'.$fileName);
            }
        } else {
            // No previous art, so just move
            rename($dirName.'/'.$tmpFileName, $dirName.'/'.$finalName);
        }
    }
}