<?php declare(strict_types = 1);
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


class Composer extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'composer';

    public function _generate(array $analyzerList): string {
        $themed = $this->rulesets->getRulesetsAnalyzers(array('Appinfo'));
        $res = $this->dump->fetchAnalysersCounts($themed);
        $sources = $res->toHash('analyzer', 'count');

        $configureDirectives = json_decode(file_get_contents($this->config->dir_root . '/data/configure.json'));
        // List of extensions that must be avoided
        $noExtensions = parse_ini_file($this->config->dir_root . '/data/php_no_extension.ini');
        $noExtensions = $noExtensions['ext'];

        $composerPath = $this->config->projects_root . '/projects/' . $this->config->project . '/code/composer.json';
        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath));
        } else {
            $composer = new \stdClass();

            $composer->name = $this->config->project_name;   //
            $composer->description = '';                     //
            $composer->type = 'library';                     // default value
            $composer->keywords = array();                   // where to find them ?
            $composer->homepage = '';                        //
            $composer->license = '';                         //

            $composer->support = new \stdClass();
            if ($this->config->project_url !== null) {
                $composer->support->source = $this->config->project_url;
            }

            $composer->require = new \stdClass();

    //"php": "~5.4 || ^7.0"
            $composer->require->php = '^7.0';
        }

        foreach($configureDirectives as $ext => $details) {
            if (in_array($ext, $noExtensions)) {
                continue;
            }

            if (isset($sources[$details->analysis]) && $sources[$details->analysis] > 1) {
                $extName = 'ext-' . $ext;
                if (!isset($composer->require->{$extName})) {
                    $composer->require->{$extName} = '*';
                }
            }
        }

        $final = json_encode($composer, \JSON_PRETTY_PRINT);

        return $final;
    }

    public function dependsOnAnalysis(): array {
        return array('Appinfo',
                     );
    }
}

?>