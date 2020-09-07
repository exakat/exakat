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


class TypeSuggestion extends Reports {
    const FILE_EXTENSION = 'html';
    const FILE_FILENAME  = 'typehint.suggestion';

    const NO_SUGGESTION = '&nbsp;';

    protected $finalName     = null;
    private $tmpName         = '';
    private $classes         = array();

    public function generate(string $folder, string $name = self::FILE_FILENAME): string {
        if ($name === self::STDOUT) {
            print "Can't produce SimpleHtml format to stdout\n";
            return '';
        }

        $colorClass     = '#fdb863';
        $colorMethod    = '#fee0b6';
        $colorProperty  = '#d0d1e6';
        $colorParameter = '#a6bddb';
        $colorReturn    = '#74a9cf';

        $list = $this->rulesets->getRulesetsAnalyzers(array('Typehints'));
        $results = $this->dump->fetchAnalysers($list);
        $results->load();

        $suggestions = array();
        foreach($results->toArray() as $row) {

            list(, $type) = explode('/', $row['analyzer']);
            if (preg_match('/function (\S+)\\(/', $row['fullcode'], $r)) {
                // function, method, with name : for return type
                $suggestions[$row['file']][$row['line']][$r[1]][] = $type;
            } elseif (preg_match('/(\$\\S+)/', $row['fullcode'], $r)) {
                // $parameter alone. it is for an parameter
                $suggestions[$row['file']][$row['line']][$r[1]][] = $type;
            } elseif (preg_match('/function \\(.*?\\) /', $row['fullcode'], $r)) {
                // closure : for return type
                $suggestions[$row['file']][$row['line']][$row['file'] . ':' . $row['line']][] = $type;
            } elseif (preg_match('/fn \(.*?\) => /', $row['fullcode'], $r)) {
                // arrow function : for return type
                $suggestions[$row['file']][$row['line']][$row['file'] . ':' . $row['line']][] = $type;
            } else {
                display('Cannot find typehints for ' . $row['fullcode'] . "\n");
            }
        }

        $stats = array('propertiesTotal' => 0,
                       'propertiesTyped' => 0,
                       'propertiesSugg'  => 0,
                       'parametersTotal' => 0,
                       'parametersTyped' => 0,
                       'parametersSugg'  => 0,
                       'returnTotal'     => 0,
                       'returnTyped'     => 0,
                       'returnSugg'      => 0,
                        );

        $html = array('Functions'      => array(),
                      'Closures'       => array(),
                      'Arrowfunctions' => array(),
                     );
        $complete = array('Functions'      => array(),
                          'Closures'       => array(),
                          'Arrowfunctions' => array(),
                     );

        // Properties Type hints
        $res = $this->dump->fetchTableProperties();
        foreach($res->toArray() as $row) {
            ++$stats['propertiesTotal'];
            if (!empty($row['typehint'])) {
                $list = self::NO_SUGGESTION;
                ++$stats['propertiesTyped'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$row['property']])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$row['property']], function ($x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['propertiesSugg'];
            } else {
                $list = self::NO_SUGGESTION;
            }

            $fullnspath = explode('::', $row['fullnspath'])[0];
            $className = $this->makeClassName($row['type'], $row['class'], $fullnspath);
            $classId = $this->getClassId($className);

            $html[$classId]['Properties'][] = <<<HTML
<td style="background-color: $colorProperty; vertical-align: top;">$row[property]</td>
<td style="background-color: $colorProperty; vertical-align: top;">$row[typehint]</td>
<td style="background-color: $colorProperty; vertical-align: top;">$list</td>
HTML;
            if (!isset($complete[$classId]['Properties'])) {
                $complete[$classId]['Properties'] = true;
            }
            $complete[$classId]['Properties'] = $complete[$classId]['Properties'] && !empty($row['typehint']);
        }

        // Arguments Type hints for functions
        $res = $this->dump->fetchTableFunctionsByArgument();
        foreach($res->toArray() as $row) {
            $id = isset($suggestions[$row['file']][$row['line']][$row['function']]) ? $row['function'] : $row['file'] . ':' . $row['line'];
            ++$stats['parametersTotal'];
            if (!empty($row['typehint'])) {
                $list = self::NO_SUGGESTION;
                ++$stats['parametersTyped'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$row['argument']])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$row['argument']], function (string $x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['parametersSugg'];
            } else {
                $list = self::NO_SUGGESTION;
            }

            $fullnspath = $row['fullnspath'];
            $type = ucfirst($row['type']) . 's';

            $html[$type][$id][(int) $row['rank']] = <<<HTML
<td style="background-color: $colorParameter; vertical-align: top;">$row[argument]</td>
<td style="background-color: $colorParameter; border-right-style: none; border-left-style: none;vertical-align: top;">$row[typehint]</td>
<td style="background-color: $colorParameter; border-left-style: none; vertical-align: top;">$list</td>
HTML;
            if (!isset($complete[$type][$id])) {
                $complete[$type][$id] = true;
            }
            $complete[$type][$id] = $complete[$type][$id] && !empty($row['typehint']);
        }

        // Arguments Type hints
        $res = $this->dump->fetchTableMethodsByArgument();
        foreach($res->toArray() as $row) {
            ++$stats['parametersTotal'];
            if (!empty($row['typehint'])) {
                $list = self::NO_SUGGESTION;
                ++$stats['parametersTyped'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$row['argument']])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$row['argument']], function (string $x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['parametersSugg'];
            } else {
                $list = self::NO_SUGGESTION;
            }

            $fullnspath = explode('::', $row['fullnspath'])[0];
            $className = $this->makeClassName($row['citType'], $row['citName'], $fullnspath);
            $classId = $this->getClassId($className);

            $html[$classId][$row['method']][(int) $row['rank']] = <<<HTML
<td style="background-color: $colorParameter; vertical-align: top;">$row[argument]</td>
<td style="background-color: $colorParameter; border-right-style: none; border-left-style: none;vertical-align: top;">$row[typehint]</td>
<td style="background-color: $colorParameter; border-left-style: none; vertical-align: top;">$list</td>
HTML;
            if (!isset($complete[$classId][$row['method']])) {
                $complete[$classId][$row['method']] = true;
            }
            $complete[$classId][$row['method']] = $complete[$classId][$row['method']] && !empty($row['typehint']);
        }

        // Return Typehints for functions
        $res = $this->dump->fetchTableFunctionsByReturntype();
        foreach($res->toArray() as $row) {
            ++$stats['returnTotal'];
            $id = isset($suggestions[$row['file']][$row['line']][$row['function']]) ? $row['function'] : $row['file'] . ':' . $row['line'];
            if (!empty($row['returntype'])) {
                $list = self::NO_SUGGESTION;
                ++$stats['returnTyped'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$id])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$id], function (string $x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['returnSugg'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$id])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$id], function (string $x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['returnSugg'];
            } else {
                $list = self::NO_SUGGESTION;
            }

            $fullnspath = $row['fullnspath'];
            $type = ucfirst($row['type']) . 's';

            $html[$type][$id][-1] = <<<HTML
<td style="background-color: $colorReturn; vertical-align: top;">: return</td>
<td style="background-color: $colorReturn; vertical-align: top;">$row[returntype]</td>
<td  style="background-color: $colorReturn; vertical-align: top;">$list</td>
HTML;
            if (!isset($complete[$type][$id])) {
                $complete[$type][$id] = true;
            }
            $complete[$type][$id] = $complete[$type][$id] && !empty($row['returntype']);
        }

        // Return Type hints
        $res = $this->dump->fetchTableMethods();
        foreach($res->toArray() as $row) {
            if (in_array(mb_strtolower($row['method']), array('__construct', '__destruct', '__get', '__set', '__call', '__callstatic', '__isset', '__clone'))) {
                continue;
            }
            ++$stats['returnTotal'];
            if (!empty($row['returntype'])) {
                $list = self::NO_SUGGESTION;
                ++$stats['returnTyped'];
            } elseif (isset($suggestions[$row['file']][$row['line']][$row['method']])) {
                $s = array_filter($suggestions[$row['file']][$row['line']][$row['method']], function (string $x): bool { return $x !== 'CouldNotType'; });
                $list = $this->toHtmlList($s);
                ++$stats['returnSugg'];
            } else {
                $list = self::NO_SUGGESTION;
            }

            $fullnspath = explode('::', $row['fullnspath'])[0];
            $className = $this->makeClassName($row['type'], $row['class'], $fullnspath);
            $classId = $this->getClassId($className);

            $html[$classId][$row['method']][-1] = <<<HTML
<td style="background-color: $colorReturn; vertical-align: top;">: return</td>
<td style="background-color: $colorReturn; vertical-align: top;">$row[returntype]</td>
<td  style="background-color: $colorReturn; vertical-align: top;">$list</td>
HTML;
            if (!isset($complete[$classId][$row['method']])) {
                $complete[$classId][$row['method']] = true;
            }
            $complete[$classId][$row['method']] = $complete[$classId][$row['method']] && !empty($row['returntype']);
        }

        if (empty($html['Functions'])) {
            unset($html['Functions']);
        }
        if (empty($html['Closures'])) {
            unset($html['Closures']);
        }
        if (empty($html['Arrowfunctions'])) {
            unset($html['Arrowfunctions']);
        }

        foreach($html as $className => &$methods) {
            $classCount = 0;
            foreach($methods as $methodName => &$returnAndArgs) {
                ksort($returnAndArgs);
                $classCount += count($returnAndArgs);

                $first = array_shift($returnAndArgs);
                switch($className) {
                    case 'Closures' :
                        $methodFullcode = ' function ( )<br /><div style="color: gray">' . $methodName . '</div>';
                        break;

                    case 'Arrowfunctions' :
                        $methodFullcode = ' fn ( ) =><br /><div style="color: gray">' . $methodName . '</div>';
                        break;

                    default:
                        switch($className) {
                            case 'Properties' :
                            $methodFullcode = $methodName;
                            break;

                        default:
                            $methodFullcode = ' function ' . $methodName . '()';
                        }
                }

                $returnAndArgs = implode(PHP_EOL,
                                         array_merge(array('<td rowspan="' . (count($returnAndArgs) + 1) . '" style="background-color: ' . $colorMethod . '; border: black 1px solid; vertical-align: top">'
                                                           . $methodFullcode
                                                           . ( $complete[$className][$methodName] ? ' &#x2705; ' : '')
                                                            . '</td>' . $first),
                                             array_map(function ($x) { return '<tr>' . $x . '</tr>';}, $returnAndArgs))
                                        );
            }

            $first = array_shift($methods);
            $status = array_reduce($complete[$className], function (bool $carry = true, bool $item = false): bool { return $carry && $item; }, true);
            if (isset($complete[$className]['Properties'])) {
                $statusMethods = array_reduce(array_slice($complete[$className], 1), function (bool $carry = true, bool $item = false): bool { return $carry && $item; }, true);
                $statusProperties = ($complete[$className]['Properties'] ?? true) === true;
            } else {
                $statusMethods = array_reduce($complete[$className], function (bool $carry = true, bool $item = false): bool { return $carry && $item; }, true);
                $statusProperties = true;
            }

            if ($status === true) { // all typehinted
                $statusHtml = ' &#x2705; ';
            } elseif ($statusMethods === true && $statusProperties === false) {
                $statusHtml = 'P : -; M : &#x2705; ';
            } elseif ($statusMethods === false && $statusProperties === true) {
                $statusHtml = 'P : &#x2705;; M : - ';
            } else {
                $statusHtml = '';
            }

            $methods = '<tr >' . implode(PHP_EOL,
                               array_merge(array('<td style="background-color: ' . $colorClass . '; vertical-align: top; border: border:black 1px solid;" rowspan="' . $classCount . '">'
                                                . ($this->classes[$className] ?? $className . '<br />')
                                                . $statusHtml
                                                . '</td>' . $first),
                                          array_map(function ($x) { return '<tr>' . $x . '</tr>';}, $methods))
                               ) . '</tr>';

        }
        $html[] = '</table>';

        $statsHtml = array('<table style="border:black 1px solid; border-collaspe: collapse">' .
                '<tr>
                    <th>Categories</th>
                    <th>Total</th>
                    <th>Typed</th>
                    <th>%</th>
                    <th>Suggestions</th>
                </tr>',
                );
        foreach(array('properties', 'parameters', 'return') as $category) {
            $perc = $stats["{$category}Total"] ? number_format($stats["{$category}Typed"] * 100 / $stats["{$category}Total"]) : '-';

            $statsHtml[] = "<tr>
                    <td>$category</td>
                    <td>{$stats["{$category}Total"]}</td>
                    <td>{$stats["{$category}Typed"]}</td>
                    <td>$perc %</td>
                    <td>{$stats["{$category}Sugg"]}</td>
                </tr>";
        }
        $statsHtml[] = '</table>';
        $statsHtml[] = '<p />';

        $html = implode(PHP_EOL, $statsHtml) . '<table style="border:black 1px solid; border-collaspe: collapse">' .
                '<tr>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Parameter, Returntype, Property</th>
                    <th>In code</th>
                    <th>Suggestions</th>
                </tr>' . implode(PHP_EOL, $html);

        $this->tmpName = "{$this->config->project_dir}/$name.html";

        file_put_contents("{$this->tmpName}", $html);

        return 'Ok';
    }

    protected function toHtmlList(array $array): string {
        if (empty($array)) {
            return '&nbsp;';
        }

        $translation = array('CouldBeString'   => 'string',
                             'CouldBeBoolean'  => 'bool',
                             'CouldBeNull'     => 'null',
                             'CouldBeFloat'    => 'float',
                             'CouldBeInt'      => 'int',
                             'CouldBeArray'    => 'array',
                             'CouldBeCallable' => 'callable',
                             'CouldBeIterable' => 'iterable',
                             'CouldBeVoid'     => 'void',
                             'CouldBeInt'      => 'int',
                             'CouldBeCIT'      => 'Class, Interface',
                            );

        foreach($array as &$item) {
            $item = $translation[$item] ?? $item;
        }
        unset($item);

        return implode("<br />\n", $array);
    }

    private function getClassId(string $description): int {
        if (($c = array_search($description, $this->classes)) === false) {
            $c = count($this->classes);
            $this->classes[$c] = $description;
        }

        return $c;
    }

    private function makeClassName(string $type, string $name, string $fullnspath): string {
        return $type . ' ' . $name . '<br /><div style="color: gray">' . $fullnspath . '</div>';
    }
}

?>