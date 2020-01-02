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


class Phpconfiguration extends Reports {
    const FILE_EXTENSION = 'ini-dist';
    const FILE_FILENAME  = 'php.suggested';

    public function _generate(array $analyzerList): string {
        $final = '';

        $themed = $this->rulesets->getRulesetsAnalyzers($analyzerList);
        $res = $this->dump->fetchAnalysersCounts($themed);
        $sources = $res->toHash('analyzer', 'count');
        $this->count(count($sources));

        $shouldDisableFunctions = json_decode(file_get_contents("{$this->config->dir_root}/data/shouldDisableFunction.json"), \JSON_OBJECT_AS_ARRAY);
        $functionsArray = array();
        $classesArray = array();
        foreach($shouldDisableFunctions as $ext => $toDisable) {
            if (!isset($sources[$ext])) {
                continue;
            } elseif ($sources[$ext] == 0) {
                if (isset($toDisable->functions)) {
                    $functionsArray[] = $toDisable->functions;
                }
                if (isset($toDisable->classes)) {
                    $classesArray[] = $toDisable->classes;
                }
            }
        }

        if (empty($functionsArray)) {
            $functionsList = '';
        } else {
            $functionsArray = array_merge(...$functionsArray);
            $functionsList = implode(',', $functionsArray);
        }
        if (empty($classesArray)) {
            $classesList = '';
        } else {
            $classesArray = array_merge(...$classesArray);
            $classesList = implode(',', $classesArray);
        }

        // preparing the list of PHP directives to review before using this application
        $directives = array('standard', 'bcmath', 'date', 'file',
                            'fileupload', 'mail', 'ob', 'env',
                            // standard extensions
                            'apc', 'amqp', 'apache', 'assertion', 'curl', 'dba',
                            'filter', 'image', 'intl', 'ldap',
                            'mbstring',
                            'opcache', 'openssl', 'pcre', 'pdo', 'pgsql',
                            'session', 'sqlite', 'sqlite3',
                            // pecl extensions
                            'com', 'eaccelerator',
                            'geoip', 'ibase',
                            'imagick', 'mailparse', 'mongo',
                            'trader', 'wincache', 'xcache'
                             );

        $data = array();
        foreach($sources as $analyzer => $count) {
            if ($analyzer == 'Structures/FileUploadUsage') {
                $data['File Upload'] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/fileupload.json'));
            } elseif ($analyzer == 'Php/UsesEnv') {
                $data['Environment'] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/env.json'));
            } elseif ($analyzer == 'Php/ErrorLogUsage') {
                $data['Error log'] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/errorlog.json'));
            } elseif ($analyzer === 'Php/UseBrowscap') {
                $data['Browscap'] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/browscap.json'));
            } elseif ($analyzer === 'Php/DlUsage') {
                $data['Dl'] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/enable_dl.json'));
            } elseif ($analyzer === 'Security/CantDisableFunction' ||
                      $analyzer === 'Security/CantDisableClass'
                      ) {

                $res2 = $this->dump->fetchAnalysers(array('Security/CantDisableFunction'));
                $list = $res2->getColumn('fullcode');
                $list = array_map(function (string $x): string {
                    return substr($x, 0, strpos($x, '('));
                }, $list);
                $list = array_unique($list);

                if (isset($disable)) {
                    continue;
                }
                $disable = parse_ini_file("{$this->config->dir_root}/data/disable_functions.ini");
                $suggestions = array_diff($disable['disable_functions'], $list);

                $data['Disable features'] = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/disable_functions.json"));

                // disable_functions
                $data['Disable features'][0]->suggested = implode(', ', $suggestions);
                $data['Disable features'][0]->documentation .= "\n; " . count($list) . " sensitive functions were found in the code. Don't disable those : " . implode(', ', $list);

                $res2 = $this->dump->fetchAnalysers(array('Security/CantDisableClass'));
                $list = $res2->getColumn('fullcode');
                $list = array_map(function (string $x): string {
                    return substr($x, 0, strpos($x, '('));
                }, $list);
                $list = array_unique($list);
                $suggestions = array_diff($disable['disable_classes'], $list);

                // disable_functions
                $data['Disable features'][1]->suggested = implode(',', $suggestions);
                $data['Disable features'][1]->documentation .= "\n; " . count($list) . " sensitive classes were found in the code. Don't disable those : " . implode(', ', $list);
            } else {
                $ext = substr($analyzer, 14);
                if (in_array($ext, $directives)) {
                    $data[$ext] = json_decode(file_get_contents($this->config->dir_root . '/data/directives/' . $ext . '.json'));
                }
            }
        }

        $directives = <<<'TEXT'

;;;;;;;;;;;;;;;;;;;;;;;;;;
; Suggestion for php.ini ;
;;;;;;;;;;;;;;;;;;;;;;;;;;

; The directives below are selected based on the code provided. 
; They only cover the related directives that may have an impact on the code
;
; The list may not be exhaustive
; The suggested values are not recommendations, and should be reviewed and adapted
;



TEXT;

        foreach($data as $section => $details) {
            $directives .= "[$section]\n";

            foreach((array) $details as $detail) {
                if ($detail->name == 'Extra configurations') {
                    preg_match('#(https?://[^"]+?)"#is', $detail->documentation, $url);
                    $directives .= "; More information about $section : 
;$url[1]

";
                } else {
                    $documentation = wordwrap(' ' . $detail->documentation, 80, "\n; ");
                    $directives .= ";$documentation
$detail->name = $detail->suggested

";
                }
            }

            if ($section === 'standard') {
                $directives .= ";$documentation
disable_functions = $functionsList
disable_classes = $classesList

";
            }

            $directives .= "\n\n";
        }

        $final .= "\n\n" . $directives;

        return $final;
    }

    public function dependsOnAnalysis(): array {
        return array('Appinfo',
                     );
    }

}

?>