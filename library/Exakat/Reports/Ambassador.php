<?php
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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Exakat;
use Exakat\Tasks\Helpers\BaselineStash;
use Exakat\Vcs\Vcs;
use Symfony\Component\Yaml\Yaml as Symfony_Yaml;

class Ambassador extends Emissary {
    const FILE_FILENAME  = 'report';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Ambassador';

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    protected $tmpName           = '';

    protected $frequences        = array();
    protected $timesToFix        = array();
    protected $themesForAnalyzer = array();
    protected $severities        = array();

    protected $generations       = array();
    protected $generations_files = array();

    protected $usedFiles         = array();

    protected $baseHTML          = null;

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $inventories = array('constants'          => 'Constants',
                                 'classes'            => 'Classes',
                                 'interfaces'         => 'Interfaces',
                                 'functions'          => 'Functions',
                                 'traits'             => 'Traits',
                                 'namespaces'         => 'Namespaces',
                                 'Type/Url'           => 'URL',
                                 'Type/Regex'         => 'Regular Expr.',
                                 'Type/Sql'           => 'SQL',
                                 'Type/Email'         => 'Email',
                                 'Type/GPCIndex'      => 'Incoming variables',
                                 'Type/Md5string'     => 'MD5 string',
                                 'Type/Mime'          => 'Mime types',
                                 'Type/Pack'          => 'Pack format',
                                 'Type/Printf'        => 'Printf format',
                                 'Type/Path'          => 'Paths',
                                 'Type/Shellcommands' => 'Shell',
                                 );

    private $compatibilities = array();

    public function __construct($config) {
        parent::__construct($config);

        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $this->compatibilities[$shortVersion] = "Compatibility PHP $shortVersion[0].$shortVersion[1]";
        }

        if ($this->rulesets !== null ){
            $this->frequences        = $this->rulesets->getFrequences();
            $this->timesToFix        = $this->rulesets->getTimesToFix();
            $this->themesForAnalyzer = $this->rulesets->getRulesetsForAnalyzer();
            $this->severities        = $this->rulesets->getSeverities();
        }
    }

    public function dependsOnAnalysis(): array {
        return array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56',
                     'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73', 'CompatibilityPHP74',
                     'Analyze', 'Preferences', 'Inventory', 'Performances',
                     'Appinfo', 'Appcontent', 'Dead code', 'Security', 'Suggestions', 'ClassReview',
                     'Custom',
                     );
    }

    protected function generateUsedMagic(Section $section) : void {
        $results = $this->dump->fetchAnalysers(array('Structures/ErrorMessages'));
        $results->load();

        $expr = $results->getColumn('fullcode');
        $expr = array_map(function ($x) { return trim($x, '{}');}, $expr);
        $counts = array_count_values($expr);

        $expressions = '';
        foreach($results->toArray() as $row) {
            $row['fullcode'] = trim($row['fullcode'], '{}');
            $fullcode = PHPSyntax($row['fullcode']);
            $expressions .= "<tr><td>{$row['file']}:{$row['line']}</td><td>{$counts[$row['fullcode']]}</td><td>$fullcode</td></tr>\n";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TABLE', $expressions);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'List of magic properties used in the code');
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

}

?>