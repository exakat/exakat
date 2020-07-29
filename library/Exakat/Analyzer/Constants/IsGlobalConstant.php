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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class IsGlobalConstant extends Analyzer {
    public function dependsOn(): array {
        return array('Constants/ConstantUsage',
                    );
    }

    public function analyze() : void {
        $exts = $this->rulesets->listAllAnalyzer('Extensions');

        $c = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
            $ini = $this->load($inifile, 'constants');

            if (!empty($ini[0])) {
                $c[] = $ini;
            }
        }

        $constants = array_merge(...$c);
        if (empty($constants)) {
            return ;
        }

        $constants = array_unique($constants);
        $constants = array_values($constants);
        $constantsFullNs = makeFullNsPath($constants, \FNP_CONSTANT);

        $this->analyzerIs('Constants/ConstantUsage')
             ->tokenIs('T_STRING')
             ->atomIsNot(array('Boolean', 'Null'))
             ->hasNoIn('AS')

             // Exclude PHP constants
             ->fullnspathIsNot($constantsFullNs, self::CASE_SENSITIVE)
             ->isNot('isPhp', true)

             // Check that the final fullnspath is actually \something (no multiple \)
             ->regexIs('fullnspath', '^\\\\\\\\[^\\\\\\\\]+\\$');
        $this->prepareQuery();
    }
}

?>
