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

namespace Exakat\Reports\Helpers;

class Sarif {
    private $sarif      = null;
    private $artifacts  = array();
    private $results    = array();
    private $rules      = array();
    private $rulesIndex = array();

    public function __construct() {
        $this->sarif = (object) array('version' => '2.1.0',
                                      '$schema' => 'http://json.schemastore.org/sarif-2.1.0-rtm.4',
                                      'runs'    => array((object) array('tool' => array('driver' => array('name'            => 'Exakat',
                                                                                                          'informationUri'  => 'https://www.exakat.io/',
                                                                                                          'version'         => '2.1.7',
                                                                                                          'semanticVersion' => '2.1.7',
                                                                                                          'rules'           => array(),

                                        )

                                                        )),

                                      ));
    }

    public function addRule(string $ruleId, string $title, string $description, string $severity = 'high', string $precision = 'high') {
        if (isset($this->rulesIndex[$ruleId])) {
            return;
        }
        if (!in_array($precision, array('very-high', 'high', 'medium', 'low', 'unknown'))) {
            $precision = 'unknown';
        }

        // levels : none, note, warning, error
        switch($severity) {
            case 'Critical':
            case 'Major':
                $level = 'error';
                break;

            case 'Minor':
                $level = 'warning';
                break;

            default:
            case 'Note':
            case 'None':
                $level = 'note';
                break;

        }

        $this->rulesIndex[$ruleId] = count($this->rulesIndex);
        $this->rules[$this->rulesIndex[$ruleId]] = array('id'                   => $ruleId,
//                                                           'name'             => $name,
                                                         'defaultConfiguration' => array('level' => $level,
                                                                                         // note, warning, error
                                                                                        ),
                                                         'shortDescription'     => array('text' => $title,
                                                                                        ),
                                                         'help'                 => array('text'     => $description,
//                                                                                         'markdown' => $description,
                                                                                        ),
                                                         'properties'           => array('tags'      => array(),
                                                                                         'precision' => $precision, //very-high, high, medium, low, or unknown.
                                                                                        ),
                                 );
    }


    public function addResult(string $fullcode, string $ruleId, string $fileName, int $line) {
        if (!isset($this->rulesIndex[$ruleId])) {
            print "No such rule as $ruleId\n";
            return;
        }

        $this->results[] = array('ruleId'    => $ruleId,
                                 'ruleIndex' => $this->rulesIndex[$ruleId],
                                 //"rule"      => $ruleId,  // same as ruleId?

                                 'level'     => $this->rules[$this->rulesIndex[$ruleId]]['defaultConfiguration']['level'],
                                 'message'   => array('text' => $fullcode,
                                                     ),
                                 'locations' => array(array(//'id' => '',
                                                      // 'message' => array('text' => '', )
                                                      'physicalLocation' => array(
                                                            'artifactLocation' => array('uri' => $fileName),
                                                            'region' => array('startLine'   => $line,
                                                                              'endLine'     => $line,
                                                                              'startColumn' => 1,
                                                                              'endColumn'   => 200,
                                                                            )
                                                      ),
                                    )
                                 ),
                                 'partialFingerprints' => array(
            'primaryLocationLineHash' => sha1($fileName . ':' . $line . ':' . $fullcode),
        ),
//                                 'codeFlows' => array('threadFlows' => array('locations' => array())),
                                 'relatedLocations' => array(),
//                                 'suppressions' => array('state' => 'accepted',
//                                                        )
                                 );
    }

    public function __toString() {
        $sarif = $this->sarif;
        $sarif->runs[0]->artifacts = $this->artifacts;
        $sarif->runs[0]->results   = $this->results;
        $sarif->runs[0]->tool['driver']['rules'] = $this->rules;
        return json_encode($this->sarif, JSON_PRETTY_PRINT);
    }
}

?>