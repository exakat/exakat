<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Melis;

use Exakat\Analyzer\Analyzer;

class MissingTranslation extends Analyzer {
    public function dependsOn() {
        return array('Melis/TranslationString',
                    );
    }

    public function analyze() {
        // select the available translations
        $this->analyzerIs('Melis/TranslationString')
             ->values('code');
        $res = $this->rawQuery();
        $translations = $res->toArray();
        $translations = array_unique($translations);

        //
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->hasNoIn('CONCAT') // No concatenation
             ->regexIs('noDelimiter', 'tr_')
             ->codeIsNot($translations, self::NO_TRANSLATE)
             ->goToFile()
             ->regexIsNot('fullcode', '^/language/.+\\\\.php')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
