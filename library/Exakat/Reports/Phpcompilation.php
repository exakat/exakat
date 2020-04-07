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


class Phpcompilation extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = 'compilePHP';

    protected function _generate(array $analyzerList): string {
        $themed = $this->rulesets->getRulesetsAnalyzers(array('Appinfo'));
        $res = $this->dump->fetchAnalysersCounts($themed);
        $sources = array_filter($res->toHash('analyzer', 'count'), function ($x) { return $x > -1;});
        $this->count($res->getCount());

        $configureDirectives = json_decode(file_get_contents("{$this->config->dir_root}/data/configure.json"));

        // preparing the list of PHP extensions to compile PHP with
        $return = array(<<<'TEXT'
;;;;;;;;;;;;;;;;;;;;;;;;
; PHP configure list   ;
;;;;;;;;;;;;;;;;;;;;;;;;

TEXT
        ,
        './configure');
        $pecl = array();
        foreach($configureDirectives as $configure) {
            if (isset($sources[$configure->analysis])) {
                if(!empty($configure->activate) && $sources[$configure->analysis] != 0) {
                    $return[] = ' ' . $configure->activate;
                    if (!empty($configure->others)) {
                        $return[] = '   ' . implode(PHP_EOL . '    ', $configure->others);
                    }
                    if (!empty($configure->pecl)) {
                        $pecl[] = '#pecl install ' . basename($configure->pecl) . ' (' . $configure->pecl . ')';
                    }
                } elseif(!empty($configure->deactivate) && $sources[$configure->analysis] == 0) {
                    $return[] = ' ' . $configure->deactivate;
                }
            }
        }

        $return = array_merge($return, array(
                   '',
                   '; For debug purposes',
                   ';--enable-dtrace',
                   ';--disable-phpdbg',
                   '',
                   ';--enable-zend-signals',
                   ';--disable-opcache',
            ));

        $final = '';
        if (!empty($pecl)) {
            $c = count($pecl);
            $final .= '# install ' . ( $c === 1 ? 'one' : $c) . ' extra extension' . ($c === 1 ? '' : 's') . "\n";
            $final .= implode("\n", $pecl) . "\n\n";
        }
        $final .= implode("\n", $return);

        return $final;
    }

    public function dependsOnAnalysis(): array {
        return array('Appinfo',
                     );
    }
}

?>