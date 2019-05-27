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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Reports\Helpers\Results;

class Phpcsfixer extends Reports {
    const FILE_EXTENSION = 'php';
    const FILE_FILENAME  = 'php_cs';

    public function generate($dirName, $fileName = null) {
        $analyzerList =  $this->themes->getThemeAnalyzers(array('php-cs-fixable'));
        $analysisResults = new Results($this->sqlite, $analyzerList);
        $analysisResults->load();
        $found = array_column($analysisResults->toArray(), 'analyzer');
        $found = array_unique($found);

        $phpcsfixer = json_decode(file_get_contents("{$this->config->dir_root}/data/phpcsfixer.json", \JSON_OBJECT_AS_ARRAY));
        assert(!empty($phpcsfixer), 'couldn\'t read phpcsfixer.json file');

        $config = array();
        foreach($found as $f) {
            $config[] = (array) $phpcsfixer[$f] ?? array();
            $this->count();
        }
        $config = array_merge(...$config);

        $configArray = var_export($config, true);
        $configArray = str_replace("\n", "\n                ", $configArray);

$config = <<<PHPCS
<?php

\$finder = PhpCsFixer\Finder::create()
    ->in('.')     // Change this to your code's path
    ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules(        
                $configArray
    )
    ->setFinder(\$finder);
    
PHPCS;

        if ($fileName === null) {
            return $config;
        } else {
            file_put_contents($dirName . '/' . $fileName . '.' . self::FILE_EXTENSION, $config);
            return true;
        }
    }
}

?>