<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;

class UsedDirective extends Analyzer {
    protected $directives = array();

    public function analyze(): void {
        // Processing ini_get_all ?
        // ini_set($var ? )

        // ini_set('string'
        $this->atomFunctionIs(array('\\ini_set',
                                    '\\ini_get',
                                    '\\ini_restore',
                                    '\\ini_alter',
                                    '\\iconv_set_encoding',
                                    '\\get_cfg_var',
                                    ))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->noDelimiterIs($this->directives, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        $functions = array();
        if (in_array('include_path', $this->directives, STRICT_COMPARISON)) {
            $functions[] = array('\\set_include_path',
                                 '\\get_include_path',
                                 '\\restore_include_path',
                                );
        }
        if (in_array('magic_quotes_gpc', $this->directives, STRICT_COMPARISON)) {
            $functions[] = array('\\magic_quotes_gpc',
                                );
        }

        if (in_array('magic_quotes_runtime', $this->directives, STRICT_COMPARISON)) {
            $functions[] = array('\\get_magic_quotes_runtime',
                                 '\\set_magic_quotes_runtime',
                                );
        }

        if (in_array('max_execution_time', $this->directives, STRICT_COMPARISON)) {
            $functions[] = array('\\set_time_limit',
                                );
        }

        if (empty($functions)) {
            return;
        }
        $functions = array_merge(...$functions);

        $this->atomFunctionIs($functions);
        $this->prepareQuery();
    }
}

?>
