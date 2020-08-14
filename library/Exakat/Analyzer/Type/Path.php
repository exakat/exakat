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


namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Dump\AnalyzerResults;

class Path extends AnalyzerResults {
    protected $analyzerName = 'Path';

    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        $protocols = $this->loadJson('protocols.json');
        $protocolList = array();
        foreach($protocols as $protocol => $details) {
            if ($details->path === true) {
                $protocolList[] = $protocol;
            }
        }
        $protocolList = implode('|', $protocolList);

        // /path/to/file.php (extension is necessary)
        $this->atomIs(self::STRINGS_LITERALS, self::WITHOUT_CONSTANTS)
             ->regexIs('noDelimiter', '^((?!(' . $protocolList . ')://)[^ :\\\\+&]*/)([^ :\\\\+&/]*)(\\\\.\\\\w{1,6}|/)\\$')
             ->toResults();
        $this->prepareQuery();

        $pathArgs = (array) $this->loadJson('php_filenames_arg.json');

        // fopen('/path/to/file.php')
        foreach($pathArgs as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
                 ->regexIsNot('noDelimiter', '^((?!(' . $protocolList . ')://)[^ :\\\\+&]*/)([^ :\\\\+&/]*)\\\\.\\\\w{1,6}\\$')
                 ->toResults();
            $this->prepareQuery();
        }
    }
}

?>
