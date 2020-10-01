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

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Helpers\Highchart;
use Exakat\Config;
use Exakat\Exakat;
use Exakat\Vcs\Vcs;
use Symfony\Component\Yaml\Yaml as Symfony_Yaml;
use Exakat\Configsource\DatastoreConfig;
use Exakat\Tasks\Helpers\BaselineStash;

class Emissary extends Reports {
    const FILE_FILENAME  = 'emissary';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Emissary';

    protected $projectPath     = null;
    protected $finalName       = null;
    private $tmpName           = '';

    protected $frequences        = array();
    protected $timesToFix        = array();
    protected $themesForAnalyzer = array();
    protected $severities        = array();

    protected $generations       = array();
    protected $generations_files = array();

    protected $usedFiles         = array();

    private $baseHTML = '';

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $inventories = array('constants'      => 'Constants',
                                 'classes'        => 'Classes',
                                 'interfaces'     => 'Interfaces',
                                 'functions'      => 'Functions',
                                 'traits'         => 'Traits',
                                 'namespaces'     => 'Namespaces',
                                 'Type/Url'       => 'URL',
                                 'Type/Regex'     => 'Regular Expr.',
                                 'Type/Sql'       => 'SQL',
                                 'Type/Email'     => 'Email',
                                 'Type/GPCIndex'  => 'Incoming variables',
                                 'Type/Md5string' => 'MD5 string',
                                 'Type/Mime'      => 'Mime types',
                                 'Type/Pack'      => 'Pack format',
                                 'Type/Printf'    => 'Printf format',
                                 'Type/Path'      => 'Paths',
                                 );

    private $compatibilities = array();

    public function __construct() {
        parent::__construct();

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

    protected function makeMenu(): string {
        $menuYaml = Symfony_Yaml::parseFile(__DIR__ . '/' . static::CONFIG_YAML . '.yaml');

        $menu = array('<ul class="sidebar-menu">',
                      '<li class="header">&nbsp;</li>',
                      );
        foreach($menuYaml as $section) {
            $menu[] = $this->makeMenuHtml($section);
        }

        $menu[] = '</ul>';

        return implode(PHP_EOL, $menu);
    }

    protected function makeMenuHtml(array $sections): string {
        if (isset($sections['file'])) {
            $icon = $sections['icon'] ?? 'sticky-note-o';
            $menuTitle = $sections['menu'] ?? $sections['title'];

            $menu = "<li><a href=\"$sections[file].html\"><i class=\"fa fa-$icon\"></i> <span>$menuTitle</span></a></li>";
            if (isset($sections['method'])) {
                $this->generations[] = new Section($sections);
            } else {
                $this->generations_files[] = $sections['file'];
            }
        } elseif (isset($sections['subsections'])) {
            $icon      = $sections['icon'] ?? 'sticky-note-o';
            $menuTitle = $sections['menu'] ?? $sections['title'];

            $menu = array('<li class="treeview">',
                          "<a href=\"#\"><i class=\"fa fa-$icon\"></i> <span>$menuTitle</span><i class=\"fa fa-angle-left pull-right\"></i></a>",
                          '<ul class="treeview-menu">',
                          );

            foreach($sections['subsections'] as $subsection) {
                $menu[] = $this->makeMenuHtml($subsection);
            }

            $menu[] = '</ul>';
            $menu[] = '</li>';

            $menu = implode(PHP_EOL . '  ', $menu);
        }

        return $menu;
    }

    private function initBasePage(): void {
        $baseHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/data/base.html");

        $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
        $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', (string) Exakat::BUILD);
        $project_name = $this->config->project_name;
        if (empty($project_name)) {
            $project_name = 'E';
        }
        $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $project_name);
        $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_NAME', $project_name);
        $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($project_name[0]));

        $menu = $this->makeMenu();
        $inventories = array();
        foreach($this->inventories as $fileName => $title) {
            if (strpos($fileName, '/') === false) {
                $inventory_name = $fileName;
            } else {
                $total = $this->dump->fetchAnalysersCounts(array($fileName))->toInt();
                if ($total < 1) {
                    continue;
                }
                $inventory_name = strtolower(basename($fileName));
            }
            $inventories []= "              <li><a href=\"inventories_$inventory_name.html\"><i class=\"fa fa-circle-o\"></i>$title</a></li>\n";
        }

        $rulesets = $this->dump->fetchTable('themas');
        $rulesets->filter(function (array $x): bool { return substr($x['thema'], 0, 13) === 'Compatibility';});
        $compatibilities = array_map(function (string $x): string { $v = substr($x, -2); return "              <li><a href=\"compatibility_php$v.html\"><i class=\"fa fa-circle-o\"></i>{$this->compatibilities[$v]}</a></li>\n";},
                                     $rulesets->getColumn('thema'));

        $menu = $this->injectBloc($menu, 'INVENTORIES', implode(PHP_EOL, $inventories));
        $menu = $this->injectBloc($menu, 'COMPATIBILITIES', implode(PHP_EOL, $compatibilities));
        $this->baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
    }

    protected function getBasedPage(string $file = ''): string {
        if (!file_exists("{$this->config->dir_root}/media/devfaceted/data/$file.html")) {
            display("Missing template file '$file' for " . static::class);

            return '';
        }

        $subPageHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/data/$file.html");
        $combinePageHTML = $this->injectBloc($this->baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    protected function putBasedPage(string $file, string $html): void {
        if (strpos($html, '{{BLOC-JS}}') !== false) {
            $html = str_replace('{{BLOC-JS}}', '', $html);
        }
        $html = str_replace('{{TITLE}}', "PHP Static analysis for {$this->config->project_name}", $html);

        file_put_contents("$this->tmpName/data/$file.html", $html);

        $this->usedFiles[] = "$file.html";
    }

    protected function injectBloc(string $html, string $bloc, string $content): string {
        return str_replace('{{' . $bloc . '}}', $content, $html);
    }

    public function generate(string $folder, string $name = self::FILE_FILENAME): string {
        if ($name === self::STDOUT) {
            print "Can't produce Emissary format to stdout\n";

            return '';
        }

        if ($missing = $this->checkMissingRulesets()) {
            print "Can't produce Emissary format. There are " . count($missing) . ' missing rulesets : ' . implode(', ', $missing) . ".\n";

            return '';
        }

        $this->finalName = "$folder/$name";
        $this->tmpName   = "$folder/.$name";

        $this->projectPath = $folder;

        $this->initFolder();
        $this->initBasePage();

        foreach($this->generations as $generation) {
            $method = $generation->method;
            if (!method_exists($this, $method)) {
                print "Warning : no such method as $method; Skipping\n";
                continue;
            }
            $this->$method($generation);
        }

        foreach($this->generations_files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();

        return '';
    }

    protected function initFolder(): void {
        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        if (!copyDir("{$this->config->dir_root}/media/devfaceted", $this->tmpName)) {
            print "Error while preparing the folder. A copy failed\n";
            return;
        }
    }

    protected function cleanFolder(): void {
        if (file_exists("{$this->tmpName}/data/base.html")) {
            unlink("{$this->tmpName}/data/base.html");
            unlink("{$this->tmpName}/data/menu.html");
            unlink("{$this->tmpName}/data/empty.html");
        }

        $files = glob("{$this->tmpName}/data/*.html");
        $files = array_map('basename', $files);
        foreach(array_diff($files, $this->usedFiles) as $file) {
            unlink("{$this->tmpName}/data/$file");
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display("{$this->finalName} folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    protected function setPHPBlocs(string $description): string {
        $description = preg_replace_callback("#<\?php(.*?)\n\?>#is", function (array $x): string {
            $return = '<pre style="border: 1px solid #ddd; background-color: #f5f5f5;">&lt;?php ' . PHP_EOL . PHPSyntax($x[1]) . '?&gt;</pre>';
            return $return;
        }, $description);

        return $description;
    }

    protected function generateDocumentation(Section $section): void {
        $analyzersList = array_merge($this->rulesets->getRulesetsAnalyzers($this->dependsOnAnalysis()));
        $analyzersList = array_unique($analyzersList);

        $baseHTML = $this->getBasedPage($section->source);
        $docHTML = array();

        foreach($analyzersList as $analyzerName) {
            $analyzer = $this->rulesets->getInstance($analyzerName, null, $this->config);
            assert ($analyzer instanceof Analyzer, "Could not get an analyzer for $analyzerName in the documentation\n");

            $description = $this->docs->getDocs($analyzerName);
            assert(isset($description['name'], $description['description']), "Could not get a name or description for $analyzerName in the documentation\n");

            $analyzersDocHTML = '<h2><a href="issues.html#analyzer=' . $this->toId($analyzerName) . '" id="' . $this->toId($analyzerName) . '">' . $description['name'] . '</a></h2>';

            $badges = array();
            $exakatSince = $description['exakatSince'] ?? '';
            if(!empty($exakatSince)){
                $badges[] = "[Since $exakatSince]";
            }

            $badges[] = '[ -P ' . $analyzer->getInBaseName() . ' ]';
            if (isset($description['name'])) {
                $badges[] = '[ <a href="https://exakat.readthedocs.io/en/latest/Rules.html#' . $this->toOnlineId($description['name']) . '">Online docs</a> ]';
            }

            $versionCompatibility = $description['phpversion'];
            if ($versionCompatibility !== Analyzer::PHP_VERSION_ANY) {
                if (strpos($versionCompatibility, '+') !== false) {
                    $versionCompatibility = substr($versionCompatibility, 0, -1) . ' and more recent ';
                } elseif (strpos($versionCompatibility, '-') !== false) {
                    $versionCompatibility = ' older than ' . substr($versionCompatibility, 0, -1);
                }
                $badges[] = '[ PHP ' . $versionCompatibility . ']';
            }

            $analyzersDocHTML .= '<p>' . implode(' - ', $badges) . '</p>';
            $description = $description['description'];
            static $regex;
            if (empty($regex)) {
                $php_native_functions = parse_ini_file("{$this->config->dir_root}/data/php_functions.ini")['functions'];
                usort($php_native_functions, function (string $a, string $b): int { return strlen($b) <=> strlen($a);} );
                $regex = '/(' . implode('|', $php_native_functions) . ')\(\)/m';
            }
            $description = preg_replace($regex, '`\1() <https://www.php.net/\1>`_', $description);

            $analyzersDocHTML .= '<p>' . nl2br($this->setPHPBlocs($description)) . '</p>';
            $analyzersDocHTML  = rst2quote($analyzersDocHTML);
            $analyzersDocHTML  = rst2htmlLink($analyzersDocHTML);
            $analyzersDocHTML  = rst2literal($analyzersDocHTML);
            $analyzersDocHTML  = rsttable2html($analyzersDocHTML);
            $analyzersDocHTML  = rstlist2html($analyzersDocHTML);

            $clearphp = $description['clearphp'] ?? '';
            if(!empty($clearphp)){
                $analyzersDocHTML.='<p>This rule is named <a target="_blank" href="https://github.com/dseguy/clearPHP/blob/master/rules/' . $clearphp . '.md">' . $clearphp . '</a>, in the clearPHP reference.</p>';
            }
            $docHTML[] = $analyzersDocHTML;
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', implode(PHP_EOL, $docHTML));
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/highlight.pack.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateFavorites(Section $section): void {
        $baseHTML = $this->getBasedPage($section->source);

        $favorites = new Favorites();
        $favoritesRules = $this->getTopFile($this->rulesets->getRulesetsAnalyzers(array('Favorites')));
        $favoritesList = json_decode($favorites->generate($favoritesRules, self::INLINE));

        $html = array();
        $highchart = new Highchart();

        foreach(array_keys((array) $favoritesList) as $analyzer) {
            $analyzerList = $this->dump->fetchHashAnalyzer($analyzer)->toHash('key', 'value');

            $table = array();
            $values = array();
            $name = $this->docs->getDocs($analyzer, 'name');

            $total = 0;
            foreach($analyzerList as $key => $value) {
                $table []= '
                <div class="clearfix">
                   <div class="block-cell">' . makeHtml($key) . '</div>
                   <div class="block-cell text-center">' . $value . '</div>
                 </div>
';
                if ($value > 0) {
                    $values[] = array('label' => $key,
                                      'value' => (int) $value);
                }
                $total += $value;
            }

            if (($repeat = 4 - count($analyzerList)) > 0) {
                $table []= str_repeat('
                <div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>
', $repeat );
            }
            $table = implode('', $table);

            // Ignore if we have no occurrences
            if ($total === 0) {
                continue;
            }

            $html[] = <<<HTML
            <div class="col-md-3">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><a href="favorites_issues.html#analyzer=$analyzer" title="$name">$name</a></h3>
                </div>
                <div class="box-body chart-responsive">
                  <div id="donut-chart_$name"></div>
                  <div class="clearfix">
                    <div class="block-cell bold">Number</div>
                    <div class="block-cell bold text-center">Count</div>
                  </div>
                  $table
                </div>
                <!-- /.box-body -->
              </div>
            </div>
HTML;
            if (count($html) % 5 === 0) {
                $html[] = '          </div>
          <div class="row">';
            }

            $highchart->addDonut('donut-chart_' . $name,  $values);
        }

        $donut = (string) $highchart;

        $html = '<div class="row">' . implode(PHP_EOL, $html) . '</div>';

        $baseHTML = $this->injectBloc($baseHTML, 'FAVORITES', $html);
        $baseHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $donut);
        $baseHTML = $this->injectBloc($baseHTML, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $baseHTML);
    }

    protected function generateDashboard(Section $section): void {
        $baseHTML = $this->getBasedPage($section->source);

        $tags = array();
        $code = array();

        // Bloc top left
        $hashData = $this->getHashData();
        $finalHTML = $this->injectBloc($baseHTML, 'BLOCHASHDATA', $hashData);

        // bloc Issues
        $issues = $this->getIssuesBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCISSUES', $issues['html']);

        // bloc severity
        $severity = $this->getSeverityBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, 'BLOCSEVERITY', $severity['html']);

        // Marking the audit date
        $this->makeAuditDate($finalHTML);

        // top 10
        $fileHTML = $this->getTopFile($this->rulesets->getRulesetsAnalyzers($this->themesToShow));
        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers($this->rulesets->getRulesetsAnalyzers($this->themesToShow));
        $finalHTML = $this->injectBloc($finalHTML, 'TOPANALYZER', $analyzerHTML);

        $highchart = new Highchart();

        $highchart->addDonut('donut-chart_issues',  $issues['script']);
        $highchart->addDonut('donut-chart_severity', $severity['script']);

        $fileOverview = $this->getFileOverview();
        $highchart->addSeries('filename',
                              $fileOverview['scriptDataFiles'],
                              array('name' => 'Critical', 'data' => $fileOverview['scriptDataCritical']),
                              array('name' => 'Major',    'data' => $fileOverview['scriptDataMajor']),
                              array('name' => 'Minor',    'data' => $fileOverview['scriptDataMinor']),
                              array('name' => 'None',     'data' => $fileOverview['scriptDataNone'])
                              );

        $analyzerOverview = $this->getAnalyzerOverview();
        $highchart->addSeries('container',
                              $analyzerOverview['scriptDataAnalyzer'],
                              array('name' => 'Critical', 'data' => $analyzerOverview['scriptDataAnalyzerCritical']),
                              array('name' => 'Major',    'data' => $analyzerOverview['scriptDataAnalyzerMajor']),
                              array('name' => 'Minor',    'data' => $analyzerOverview['scriptDataAnalyzerMinor']),
                              array('name' => 'None',     'data' => $analyzerOverview['scriptDataAnalyzerNone'])
                              );

        $blocjs = (string) $highchart;

        $blocjs = str_replace($tags, $code, $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateCounts(Section $section, string $hash, string $suffix = '', string $name = ''): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->fetchHashResults($hash);
        if ($res->isEmpty()) {
            $this->emptyResult($section);

            return;
        }

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['key'] . $suffix] = $value['value'];

            $html [(int) $value['value'] ]= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        krsort($html);
        $html = implode('', $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => $name, 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateClassDesignations(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        $html = array('<table class="table table-striped">',
                      '<tr><td>Namespace</td><td>Class / interface</td><td>Count</td><td>Fitting typehints</td><td>Count</td><td>As typehint</td></tr>',
                       );

        $namespaces = $this->dump->fetchTable('namespaces')->toHash('id', 'namespace');

        // il faut constuire les différentes possibilitées avant de construire le tableau,
        // afin de suivre les extends, et tous les rassembler.

        $res = $this->dump->fetchTable('cit');
        $parents = array();
        $children = array();
        $names = array();
        foreach($res->toArray() as $row) {
            if (empty($row['extends'])) {
                $parents[$row['id']] = array();
            } else {
                array_collect_by($parents, $row['id'],(intval($row['extends']) > 0 ? $row['extends'] : 'class ' . $row['extends']));
                array_collect_by($children, (intval($row['extends']) > 0 ? $row['extends'] : 'class ' . $row['extends']), $row['id']);
            }
            $names[$row['id']] = $row['type'] . ' ' . $namespaces[$row['namespaceId']] . $row['name'];
        }

        $res_implements = $this->dump->fetchTable('cit_implements')->toArray();
        $implements = array();
        foreach($res_implements as $row) {
            array_collect_by($implements, $row['implementing'], $row);
            array_collect_by($parents, $row['implementing'], (intval($row['implements']) > 0 ? $row['implements'] : 'interface ' . $row['implements']));
            array_collect_by($children, (intval($row['implements']) > 0 ? $row['implements'] : 'interface ' . $row['implements']), $row['implementing']);
        }

        /// Collect classes and interfaces that accept a class as typehint : class C extends B {} => C => [C, B]
        do {
            $toPropagate = 0;
            $parents2 = array();
            foreach($parents as $key => $aieux) {
                $cleaned = array(array());
                foreach($aieux as $id => $aieul) {
                    if (isset($parents[$aieul])) {
                        $cleaned[] = $parents[$aieul];
                        $cleaned[] = array($aieul);
                        ++$toPropagate;
                    } else {
                        $cleaned[] = array($aieul);
                    }
                }

                $parents2[$key] = array_values(array_unique(array_merge(...$cleaned)));
            }

            $toPropagate = count($parents2, 1) - count($parents, 1);
            $parents = $parents2;
        } while($toPropagate > 0);

        /// Collect classes that can fit a class used as typehint : class C extends B {} => B => [C, B]
        // children classes may fit when the parent is used as typehint. Interface don't count as result
        do {
            $toPropagate = 0;
            $children2 = array();
            foreach($children as $key => $child) {
                $cleaned = array();
                foreach($child as $id => $kid) {
                    if (isset($children[$kid])) {
                        $cleaned[] = $children[$kid];
                        $cleaned[] = array($kid);
                        ++$toPropagate;
                    } else {
                        $cleaned[] = array($kid);
                    }
                }
                $children2[$key] = array_values(array_unique(array_merge(...$cleaned)));
            }

            $toPropagate = count($children2, 1) - count($children, 1);
            $children = $children2;
        } while($toPropagate > 0);

        foreach($res->toArray() as $row) {
            $td = array();
            $td[] = '<td style="vertical-align: top">' . $namespaces[$row['namespaceId']] . '</td>';
            $td[] = '<td style="vertical-align: top">' . $row['type'] . ' ' . $row['name'] . '</td>';

            // fitting typehint
            $list = array();
            if ($row['type'] == 'class') {
                $list[] = $names[$row['id']] ?? $row['id'];
            }
            if (isset($parents[$row['id']])) {
                foreach($parents[$row['id']] as $higher) {
                    $list[] = $names[$higher] ?? $higher;
                }
            }
            sort($list);
            if (empty($list)) {
                $td[] = '<td>0</td>';
                $td[] = '<td>&nbsp;</td>';
            } else {
                $td[] = '<td style="vertical-align: top">' . count($list) . '</td>';
                $td[] = '<td><ul><li>' . implode('</li><li>', $list) . '</li></ul></td>';
            }

            // when used as typehint
            $list = array();
            if ($row['type'] == 'class') {
                $list[] = $names[$row['id']] ?? $row['id'];
            }
            if (isset($children[$row['id']])) {
                foreach($children[$row['id']] as $higher) {
                    $n = $names[$higher] ?? $higher;
                    if ($n[0] === 'c') {
                        $list[] = $n;
                    }
                }
            }
            sort($list);
            if (empty($list)) {
                $td[] = '<td>0</td>';
                $td[] = '<td>&nbsp;</td>';
            } else {
                $td[] = '<td style="vertical-align: top">' . count($list) . '</td>';
                $td[] = '<td><ul><li>' . implode('</li><li>', $list) . '</li></ul></td>';
            }

            $html[] = '<tr>' . join('', $td) . '</tr>';
        }

        $html[] = '</table>';

        $finalHTML = $this->injectBloc($finalHTML, 'DESCRIPTION', '');
        $finalHTML = $this->injectBloc($finalHTML, 'CONTENT', implode(PHP_EOL, $html));
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateLocalVariableCounts(Section $section): void {
        $this->generateCounts($section, 'Local Variable Counts', ' var.', 'Local variables');
    }

    protected function generateParameterCounts(Section $section): void {
        $this->generateCounts($section, 'ParameterCounts', ' param.', 'Parameters');
    }

    protected function generatePropertyCounts(Section $section): void {
        $this->generateCounts($section, 'CIT property counts', ' prop.', 'Properties');
    }

    protected function generateMethodCounts(Section $section): void {
        $this->generateCounts($section, 'CIT method counts', ' method.', 'Methods');
    }

    protected function generateClassConstantCounts(Section $section): void {
        $this->generateCounts($section, 'CIT class constant counts', ' const.', 'Class Constant');
    }

    protected function generateTailoredRuleset(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        $res = $this->dump->fetchAnalysersCounts($list);
        $rulesets = array('[ruleset_name]');
        foreach($res->toArray() as $r) {
            $rulesets[] = "analyzer[] = \"$r[analyzer]\";";
        }

        $rulesets = implode(PHP_EOL, $rulesets);

        $finalHTML = $this->injectBloc($finalHTML, 'RULESET',  $rulesets);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateExtensionsBreakdown(Section $section): void {
        // List of extensions used
        $extensionList = $this->dump->getExtensionList();

        $html = array();
        $data = array();
        foreach ($extensionList->toArray() as $value) {
            $shortName = str_replace('Extensions/Ext', 'ext/', $value['analyzer']);
            $data[$value['analyzer']] = $value['count'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $shortName . '</div>
                      <div class="block-cell-issue text-center">' . $value['count'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Extensions', $data, $html);
    }

    protected function generatePHPFunctionBreakdown(Section $section): void {
        // List of php functions used
        $res = $this->dump->fetchTable('phpStructures');
        $res->filter(function (array $x): bool { return $x['type'] === 'function'; });
        $res->order(function (array $a, array $b): int { return $b['count'] <=> $a['count']; });

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['name']] = $value['count'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['name'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['count'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'PHP Native Functions', $data, $html);
    }

    protected function generateClassTypehints(Section $section): void {
        // List of typehints used
        $res = $this->dump->fetchHashResults('Typehinting stats');
        $res->order(function (array $a, array $b): int { return $b['value'] <=> $a['value']; });

        $html = array();
        $data = array();
        $omit = array(  'totalArguments',
                        'allTotal',
                        'totalFunctions',
                        'methodTotal',
                        'withTypehint',
                        'allWithTypehint',
                        'methodWithTypehint',
                        'functionTotal',
                        'scalartype',
                        'functionWithTypehint2',
                        'closureTotal',
                        'argNullable',
                        'allWithReturnTypehint',
                        'interfaceTypehint',
                        'classTypehint',
                        'functionWithReturnTypehint',
                        'returnNullable',
                        'methodWithReturnTypehint',
                        'withReturnTypehint',
                        'closureWithTypehint',
                        'closureWithReturnTypehint',
                        'arrowfunctionTotal',
                        'arrowfunctionWithTypehint',
                        'arrowfunctionWithReturnTypehint',
                        'totalProperties',
                        'typedProperties',
                        'multipleTypehints',
                        'functionWithTypehint',
        );

        foreach ($res->toArray() as $value) {
            if (in_array($value['key'], $omit)) { continue; }
            $data[$value['key']] = $value['value'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Class typehint usage', $data, $html);
    }

    protected function generatePHPConstantsBreakdown(Section $section): void {
        // List of php constant used
        $res = $this->dump->fetchTable('phpStructures');
        $res->filter(function (array $x): bool { return $x['type'] === 'constant'; });
        $res->order(function (array $a, array $b): int { return $b['count'] <=> $a['count']; });

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['name']] = $value['count'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['name'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['count'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Constants', $data, $html);
    }

    protected function generatePHPClassesBreakdown(Section $section): void {
        // List of php functions used
        $res = $this->dump->fetchTable('phpStructures');
        $res->filter(function (array $x): bool { return in_array($x['type'], array('class', 'interface', 'trait'), \STRICT_COMPARISON); });
        $res->order(function (array $a, array $b): int { return $b['count'] <=> $a['count']; });

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['name']] = $value['count'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['name'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['count'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Classes', $data, $html);
    }

    protected function generateGraphList(string $filename, string $title, string $data_name, array $data, string $html): void {
        $finalHTML = $this->getBasedPage('extension_list');
        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => $data_name, 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $title);

        $this->putBasedPage($filename, $finalHTML);
    }

    public function getHashData(): string {
        $info = array(
            'Number of PHP files'                   => $this->dump->fetchHash('files')->toString(),
            'Number of lines of code'               => $this->dump->fetchHash('loc')->toString(),
            'Number of lines of code with comments' => $this->dump->fetchHash('locTotal')->toString(),
            'PHP used'                              => $this->dump->fetchHash('php_version')->toString(),
        );

        // fichier
        $totalFile = $this->dump->fetchHash('files')->toString();
        $totalFileAnalysed = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFile - $totalFileAnalysed;
        if ((int) $totalFile === 0) {
            $percentFile = 100;
        } else {
            $percentFile = abs(round($totalFileSansError / $totalFile * 100));
        }

        // analyzer
        list($totalAnalyzerUsed, $totalAnalyzerReporting) = array_values($this->getTotalAnalyzer());
        $totalAnalyzerWithoutError = $totalAnalyzerUsed - $totalAnalyzerReporting;
        if ($totalAnalyzerUsed > 0) {
            $percentAnalyzer = abs(round($totalAnalyzerWithoutError / $totalAnalyzerUsed * 100));
        } else {
            $percentAnalyzer = 100;
        }

        $html = <<<HTML
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Project Overview</h3>
        </div>
    
        <div class="box-body chart-responsive">
            <div class="row">
                <div class="sub-div">
                    <p class="title"><span># of PHP</span> files</p>
                    <p class="value">{$info['Number of PHP files']}</p>
                </div>
                <div class="sub-div">
                    <p class="title"><span>PHP</span> Used</p>
                    <p class="value">{$info['PHP used']}</p>
                 </div>
            </div>
            <div class="row">
                <div class="sub-div">
                    <p class="title"><span>PHP</span> LoC</p>
                    <p class="value">{$info['Number of lines of code']}</p>
                </div>
                <div class="sub-div">
                    <p class="title"><span>Total</span> LoC</p>
                    <p class="value">{$info['Number of lines of code with comments']}</p>
                </div>
            </div>
            <div class="row">
                <div class="sub-div">
                    <div class="title">Files free of issues (%)</div>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: {$percentFile}%">
                            {$totalFileSansError}
                        </div><div style="color:black; text-align:center;">{$totalFileAnalysed}</div>
                    </div>
                    <div class="pourcentage">{$percentFile}%</div>
                </div>
                <div class="sub-div">
                    <div class="title">Analyzers free of issues (%)</div>
                    <div class="progress progress-sm active">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: {$percentAnalyzer}%">
                            {$totalAnalyzerWithoutError}
                        </div><div style="color:black; text-align:center;">{$totalAnalyzerReporting}</div>
                    </div>
                    <div class="pourcentage">{$percentAnalyzer}%</div>
                </div>
            </div>
        </div>
    </div>
HTML;

        return $html;
    }

    public function getIssuesBreakdown(): array {
        $rulesets = array('Code Smells'  => 'Analyze',
                          'Dead Code'    => 'Dead code',
                          'Security'     => 'Security',
                          'Performances' => 'Performances');

        $data = array();
        foreach ($rulesets AS $key => $categorie) {
            $list = $this->rulesets->getRulesetsAnalyzers(array($categorie));
            $res = $this->dump->fetchAnalysersCounts($list);
            $res->filter(function (array $x): bool { return $x['count'] >= -1;});
            $counts = $res->getColumn('count');
            $data[] = array('label' => $key, 'value' => array_sum($counts));
        }

        // ordonné DESC par valeur
        uasort($data, function (array $a, array $b): int {
            return $b['value'] <=> $a['value'];
        });
        $issuesHtml = '';
        $dataScript = array();

        foreach ($data as $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript[] = $value;
        }

        $nb = 4 - count($data);
        $filler = '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        if ($nb > 0) {
            $issuesHtml .= str_repeat($filler, $nb);
        }

        return array('html'   => $issuesHtml,
                     'script' => $dataScript);
    }

    public function getSeverityBreakdown(): array {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        $res = $this->dump->getSeverityBreakdown($list);

        $html = array();
        $dataScript = array();
        foreach ($res->toArray() as $value) {
            $html []= <<<HTML
<div class="clearfix">
    <div class="block-cell">$value[label]</div>
    <div class="block-cell text-center">$value[value]</div>
</div>
HTML;
            $dataScript[] = $value;
        }

        if (($c = 4 - $res->getCount()) > 0) {
            $html []= str_repeat('<div class="clearfix">
                       <div class="block-cell">&nbsp;</div>
                       <div class="block-cell text-center">&nbsp;</div>
                     </div>', $c);
        }
        $html = implode('', $html);

        return array('html'   => $html,
                     'script' => $dataScript);
    }

    protected function getTotalAnalysedFile(): int {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);

        return $this->dump->getAnalyzedFiles($list);
    }

    protected function generateAnalyzers(): void {
        $analysers = $this->getAnalyzersResultsCounts();

        $baseHTML = $this->getBasedPage('analyses');
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML .= '<tr>';

            $analyserHTML.= '<td><a href="issues.html#analyzer=' . $this->toId($analyser['analyzer']) . '" title="' . $analyser['label'] . '">' . $analyser['label'] . '</a></td>
                        <td>' . $analyser['recipes'] . '</td>
                        <td>' . $analyser['issues'] . '</td>
                        <td>' . $analyser['files'] . '</td>
                        <td>' . $analyser['severity'] . '</td>
                        <td>' . $this->frequences[$analyser['analyzer']] . ' %</td>';
            $analyserHTML .= '</tr>';
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');

        $this->putBasedPage('analyses', $finalHTML);
    }

    protected function getAnalyzersResultsCounts(): array {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);

        $result = $this->dump->getAnalyzersResultsCounts($list);

        $return = array();
        foreach ($result->toArray() as $row) {
            $row['label'] = $this->docs->getDocs($row['analyzer'], 'name');
            $row['recipes' ] =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);

            $return[] = $row;
        }

        return $return;
    }

    private function generateNoIssues(Section $section): void {
        $list = $this->rulesets->getRulesetsAnalyzers(array(
        'Analyze',
        'Security',
        'Performances',
        'CompatibilityPHP53',
        'CompatibilityPHP54',
        'CompatibilityPHP55',
        'CompatibilityPHP56',
        'CompatibilityPHP70',
        'CompatibilityPHP71',
        'CompatibilityPHP72',
        'CompatibilityPHP73',
        'CompatibilityPHP74',
        'CompatibilityPHP80',
        ));

        $result = $this->dump->fetchAnalysersCounts($list);
        $result->filter(function (array $x): bool { return substr($x['analyzer'], 0, 7) !== 'Common';});

        $baseHTML = $this->getBasedPage($section->source);

        $filesHTML = array();
        foreach ($result->toArray() as $row) {
            $analyzer = $this->rulesets->getInstance($row['analyzer'], null, $this->config);

            if ($analyzer === null) {
                continue;
            }

            $filesHTML []= '<tr><td>' . $this->makeDocLink($row['analyzer']) . '</td></tr>';
        }
        $filesHTML = implode(PHP_EOL, $filesHTML);

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateFiles(Section $section): void {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage($section->source);
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= '<tr>';


            $filesHTML.='<td> <a href="issues.html#file=' . $this->toId($file['file']) . '" title="' . $file['file'] . '">' . $file['file'] . '</a></td>
                        <td>' . $file['loc'] . '</td>
                        <td>' . $file['issues'] . '</td>
                        <td>' . $file['analyzers'] . '</td>';
            $filesHTML.= '</tr>';
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function getFilesResultsCounts(): array {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        $res = $this->dump->getFilesResultsCounts($list)->toHash('file');

        return $res;
    }

    protected function getFilesCount(array $list = array(), int $limit = 10): array {
        $res = $this->dump->getFileBreakdown($list);

        return array_slice($res->toArray(), 0, $limit);
    }

    protected function getTopFile(array $list, string $file = 'issues'): string {
        $data = $this->getFilesCount($list, self::TOPLIMIT);

        $html = array();
        foreach ($data as $value) {
            $html []= '<div class="clearfix">
                    <a href="' . $file . '.html#file=' . $this->toId($value['file']) . '" title="' . $value['file'] . '">
                      <div class="block-cell-name">' . $value['file'] . '</div>
                    </a>
                    <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }

        $nb = 10 - count($data);
        if ($nb > 0) {
            $html []= str_repeat('<div class="clearfix">
                          <div class="block-cell-name">&nbsp;</div>
                          <div class="block-cell-issue text-center">&nbsp;</div>
                      </div>', $nb);
        }

        return implode(PHP_EOL, $html);
    }

    protected function getFileOverview(): array {
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('file');
        unset($severities['None']);
        uasort($severities, function (array $a, array $b): int { return array_sum($b) <=> array_sum($a); });
        $severities = array_slice($severities, 0, 10);

        foreach ($severities as $file => $value) {
            $xAxis[]        = $file;
            $dataCritical[] = $value['Critical'] ?? 0;
            $dataMajor[]    = $value['Major']    ?? 0;
            $dataMinor[]    = $value['Minor']    ?? 0;
            $dataNone[]     = $value['None']     ?? 0;
        }

        return array(
            'scriptDataFiles'    => $xAxis,
            'scriptDataCritical' => $dataCritical,
            'scriptDataMajor'    => $dataMajor,
            'scriptDataMinor'    => $dataMinor,
            'scriptDataNone'     => $dataNone,
        );
    }

    protected function getAnalyzersCount(int $limit): array {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        $res = $this->dump->getAnalyzersCount($list);

        return array_slice($res->toArray(), 0, $limit);
    }

    protected function getTopAnalyzers(array $list, string $file = 'issues'): string {
        $res = $this->dump->getTopAnalyzers($list, self::TOPLIMIT);

        $data = array();
        foreach ($res->toArray() as $row) {
            $data[] = array('label' => $this->docs->getDocs($row['analyzer'], 'name'),
                            'value' => $row['number'],
                            'name'  => $row['analyzer']);
        }

        $html = array();
        foreach ($data as $value) {
            $html []= '<div class="clearfix">
                    <a href="' . $file . '.html#analyzer=' . $this->toId($value['name']) . '" title="' . $value['label'] . '">
                      <div class="block-cell-name">' . $value['label'] . '</div> 
                    </a>
                    <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }

        $nb = 10 - count($data);
        if ($nb > 0) {
            $html []= str_repeat('<div class="clearfix">
                          <div class="block-cell-name">&nbsp;</div>
                          <div class="block-cell-issue text-center">&nbsp;</div>
                      </div>', $nb);
        }

        $html = implode(PHP_EOL, $html);

        return $html;
    }

    protected function getSeveritiesNumberBy(string $type = 'file'): array {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);

        $res = $this->dump->getSeveritiesNumberBy($list, $type);
        $return = array();
        foreach($res->toArray() as $value) {
            $return[$value[$type]][$value['severity']] = $value['count'];
        }

        return $return;
    }

    protected function getAnalyzerOverview(): array {
        $data = $this->getAnalyzersCount(self::TOPLIMIT);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $ini = $this->docs->getDocs($value['analyzer']);
            $xAxis[]        = $ini['name'];
            $dataCritical[] = empty($severities[$value['analyzer']]['Critical']) ? 0 : $severities[$value['analyzer']]['Critical'];
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major']) ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor']) ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None']) ? 0 : $severities[$value['analyzer']]['None'];
        }

        return array(
            'scriptDataAnalyzer'         => $xAxis,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerMajor'    => $dataMajor,
            'scriptDataAnalyzerMinor'    => $dataMinor,
            'scriptDataAnalyzerNone'     => $dataNone,
        );
    }

    private function generateNewIssues(Section $section): void {
        $baselines = new BaselineStash($this->config);
        $previous = $baselines->getBaseline();

        if ($previous === BaselineStash::NO_BASELINE) {
            $this->emptyResult($section);

            return;
        }

        $issues = $this->getIssuesFaceted($this->rulesets->getRulesetsAnalyzers($this->themesToShow));

        $oldissues = $this->getNewIssuesFaceted($this->rulesets->getRulesetsAnalyzers($this->themesToShow), $previous);
        $diff = array_diff($issues, $oldissues);

        $this->generateIssuesEngine($section, $diff);
    }

    private function generateIssues(Section $section): void {
        $issues = $this->getIssuesFaceted($this->rulesets->getRulesetsAnalyzers($this->themesToShow));
        $this->generateIssuesEngine($section, $issues);
    }

    protected function generateIssuesEngine(Section $section, array $issues = array()): void {
        if (empty($issues)) {
            $issues = $this->getIssuesFaceted(makeArray($this->rulesets->getRulesetsAnalyzers(makeArray($section->ruleset))));
        }

        $total = count($issues);
        $issues = implode(', ' . PHP_EOL, $issues);
        $blocjs = <<<JAVASCRIPTCODE
        
  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [
$issues
];

      var item_template =  
        '<tr>' +
          '<td width="20%"><a href="<%= "analyses_doc.html#" + obj.analyzer_md5 %>" title="Documentation for <%= obj.analyzer %>"><i class="fa fa-book"></i></a> <%= obj.analyzer %></td>' +
          '<td width="20%"><a href="<%= "codes.html#file=" + obj.file + "&line=" + obj.line %>" title="Go to code"><%= obj.file + ":" + obj.line %></a></td>' +
          '<td width="18%"><%= obj.code %></td>' + 
          '<td width="2%"><%= obj.code_detail %></td>' +
          '<td width="7%" align="center"><%= obj.severity %></td>' +
          '<td width="7%" align="center"><%= obj.complexity %></td>' +
          '<td width="16%"><%= obj.recipe %></td>' +
        '</tr>' +
        '<tr class="fullcode">' +
          '<td colspan="7" width="100%"><div class="analyzer_help"><%= obj.analyzer_help %></div><pre><code><%= obj.code_plus %></code><div class="text-right"><a target="_BLANK" href="<%= "codes.html#file=" + obj.file + "&line=" + obj.line %>" class="btn btn-info">View File</a></div></pre></td>' +
        '</tr>';
      var settings = { 
        items           : data_items,
        facets          : { 
          'analyzer'  : 'Analysis',
          'file'      : 'File',
          'severity'  : 'Severity',
          'complexity': 'Time To Fix',
          'receipt'   : 'Rulesets'
        },
        facetContainer     : '<div class="facetsearch btn-group" id=<%= id %> ></div>',
        facetTitleTemplate : '<button class="facettitle multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected"><span class="multiselect-selected-text"><%= title %></span><b class="caret"></b></button>',
        facetListContainer : '<ul class="facetlist multiselect-container dropdown-menu" style="max-height: 450px; overflow: auto;"></ul>',
        listItemTemplate   : '<li class=facetitem id="<%= id %>" data-analyzer="<%= data_analyzer %>" data-file="<%= data_file %>"><span class="check"></span><%= name %><span class=facetitemcount>(<%= count %>)</span></li>',
        bottomContainer    : '<div class=bottomline></div>',  
        resultSelector   : '#results',
        facetSelector    : '#facets',
        resultTemplate   : item_template,
        paginationCount  : 50
      }   
      $.facetelize(settings);
      
      var analyzerParam = window.location.hash.split('analyzer=')[1];
      console.log(analyzerParam);
      var fileParam = window.location.hash.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam.toLowerCase() + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam.toLowerCase() + "']").click();
      }
    });
  </script>
JAVASCRIPTCODE;

        $baseHTML = $this->getBasedPage($section->source);
        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TOTAL', (string) $total);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function getIssuesFaceted(array $ruleset): array {
        return $this->getIssuesFacetedDb($ruleset);
    }

    public function getNewIssuesFaceted(array $ruleset, string $path): array {
        $sqlite = new \Sqlite3($path);
        $res = $sqlite->query('SELECT count(*) FROM sqlite_master WHERE type = "table" AND name != "sqlite_sequence";');

        if ($res === false || $res->fetchArray(\SQLITE3_NUM)[0] < 10) {
            return array();
        }

        $result = $this->dump->fetchTable('linediff');
        $linediff = array();
        foreach($result->toArray() as $row) {
            $linediff[$row['file']][$row['line']] = $row['diff'];
        }

        $oldIssues = $this->getIssuesFacetedDb($ruleset);
        foreach($oldIssues as &$issue) {
            $i = json_decode($issue);

            // Skip wrong lines, but why ?
            if (!($i instanceof \stdClass)) {
                continue;
            }

            if (isset($linediff[$i->file]) && $i->line > -1) {
                foreach($linediff[$i->file] as $line => $diff) {
                    if ($i->line > $line) {
                        $i->line += $diff;
                    }
                }
                if ($i->line > $line) {
                    $issue = json_encode($i);
                }
            }
        }
        unset($issue);

        return $oldIssues;
    }

    public function getIssuesFacetedDb(array $ruleset): array {
        $results = $this->dump->fetchAnalysers($ruleset);
        $results->filter(function (array $x): bool { return !in_array($x['fullcode'], array('Not Compatible With PHP Version', 'Not Compatible With PHP Configuration')); });

        $TTFColors = array('Instant'  => '#5f492d',
                           'Quick'    => '#e8d568',
                           'Slow'     => '#d06960',
                           'None'     => '#89070b'
                           );

        $severityColors = array('Critical' => '#ff0000',   // red
                                'Major'    => '#FFA500',   // Orange
                                'Minor'    => '#BDB76B',   // darkkhaki
                                'None'     => '#D3D3D3',   // lightgrey
                                );

        $items = array();
        foreach($results->toArray() as $row) {
            $item = array();
            $ini = $this->docs->getDocs($row['analyzer']);
            $item['analyzer']       = $ini['name'];
            $item['analyzer_md5']   = $this->toId($row['analyzer']);
            $item['file' ]          = $row['line'] === -1 ? $this->config->project_name : $row['file'];
            $item['file_md5' ]      = $this->toId($row['file']);
            $item['code' ]          = PHPSyntax((string) $row['fullcode']);
            $item['code_detail']    = '<i class="fa fa-plus "></i>';
            $item['code_plus']      = PHPSyntax((string) $row['fullcode']);
            $item['link_file']      = $row['file'];
            $item['line' ]          = $row['line'];
            $item['severity']       = '<i class="fa fa-warning" style="color: ' . $severityColors[$this->severities[$row['analyzer']]] . '"></i>';
            $item['complexity']     = '<i class="fa fa-cog" style="color: ' . $TTFColors[$this->timesToFix[$row['analyzer']]] . '"></i>';
            $item['recipe' ]        =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);
            $lines                  = explode("\n", $ini['description']);
            $item['analyzer_help' ] = $lines[0];

            $items[] = json_encode($item);
            $this->count();
        }

        return $items;
    }

    private function getClassByType(string $type): string {
        if ($type === 'Critical' || $type === 'Long') {
            $class = 'text-orange';
        } elseif ($type === 'Major' || $type === 'Slow') {
            $class = 'text-red';
        } elseif ($type === 'Minor' || $type === 'Quick') {
            $class = 'text-yellow';
        }  elseif ($type === 'Note' || $type === 'Instant') {
            $class = 'text-blue';
        } else {
            $class = 'text-gray';
        }

        return $class;
    }

    protected function generateProcFiles(Section $section): void {
        $files = array();
        $fileList = $this->dump->fetchTable('files')->getColumn('file');
        foreach($fileList as $file) {
            $files []= "<tr><td>$file</td></tr>";
        }
        $files = implode(PHP_EOL, $files);

        $nonFiles = array();
        $ignoredFiles = $this->dump->fetchTable('ignoredFiles')->toArray();
        foreach($ignoredFiles as $row) {
            if (empty($row['file'])) { continue; }

            $nonFiles []= "<tr><td>{$row['file']}</td><td>{$row['reason']}</td></tr>";
        }
        $nonFiles = implode(PHP_EOL, $nonFiles);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'FILES', $files);
        $html = $this->injectBloc($html, 'NON-FILES', $nonFiles);
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $html);
    }

    protected function generateAnalyzersList(Section $section): void {
        $analyzers = array();

        foreach($this->rulesets->getRulesetsAnalyzers($this->themesToShow) as $analyzer) {
            $analyzers []= '<tr><td>' . $this->docs->getDocs($analyzer, 'name') . '</td><td>' . $analyzer . "</td></tr>\n";
        }
        $analyzers = implode(PHP_EOL, $analyzers);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'ANALYZERS', $analyzers);
        $html = $this->injectBloc($html, 'TITLE',     $section->title);

        $this->putBasedPage($section->file, $html);
    }

    private function generateExternalLib(Section $section): void {
        $externallibraries = json_decode(file_get_contents("{$this->config->dir_root}/data/externallibraries.json"));

        $libraries = array();
        $externallibrariesList = $this->dump->fetchTable('externallibraries')->toArray();

        foreach($externallibrariesList as $row) {
            $name = strtolower($row['library']);
            $url  = $externallibraries->{$name}->homepage;
            $name = $externallibraries->{$name}->name;
            if (empty($url)) {
                $homepage = '';
            } else {
                $homepage = "<a href=\"$url\">$row[library]</a>";
            }
            $libraries []= "<tr><td>$name</td><td>$row[file]</td><td>$homepage</td></tr>";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'LIBRARIES', implode(PHP_EOL, $libraries));
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $html);
    }

    protected function generateBugFixes(Section $section): void {
        $table = '';

        $bugfixes = exakat('methods')->getBugFixes();

        $results = $this->dump->fetchAnalysers(array('Php/MiddleVersion'));

        $rows = array();
        foreach($results->toArray() as $row) {
            $rows[strtolower(substr($row['fullcode'], 0, (int) strpos($row['fullcode'], '(')))] = $row;
        }

        foreach($bugfixes as $bugfix) {
            if (empty($bugfix['solvedIn73']) &&
                empty($bugfix['solvedIn72']) &&
                empty($bugfix['solvedIn71']) &&
                empty($bugfix['solvedIn70']) ) { continue; }

            if (!empty($bugfix['function'])) {
                if (!isset($rows[$bugfix['function']])) { continue; }

                $cve = $this->Bugfixes_cve($bugfix['cve'] ?? '');
                $table .= '<tr>
    <td>' . $bugfix['title'] . '</td>
    <td>' . ($bugfix['solvedIn73'] ? $bugfix['solvedIn73'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn72'] ? $bugfix['solvedIn72'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn71'] ? $bugfix['solvedIn71'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn70'] ? $bugfix['solvedIn70'] : '-') . '</td>
    <td>' . ($bugfix['solvedInDev'] ? $bugfix['solvedInDev'] : '-') . '</td>
    <td><a href="https://bugs.php.net/bug.php?id=' . $bugfix['bugs'] . '">#' . $bugfix['bugs'] . '</a></td>
    <td>' . $cve . '</td>
                </tr>';
            } elseif (!empty($bugfix['analyzer'])) {
                $subanalyze = $this->dump->fetchAnalysersCounts(array($bugfix['analyzer']))->toString('count');

                $cve = $this->Bugfixes_cve($bugfix['cve']);

                if ($subanalyze === 0) { continue; }
                $table .= '<tr>
    <td>' . $bugfix['title'] . '</td>
    <td>' . ($bugfix['solvedIn73'] ? $bugfix['solvedIn73'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn72'] ? $bugfix['solvedIn72'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn71'] ? $bugfix['solvedIn71'] : '-') . '</td>
    <td>' . ($bugfix['solvedIn70'] ? $bugfix['solvedIn70'] : '-') . '</td>
    <td>' . ($bugfix['solvedInDev'] ? $bugfix['solvedInDev'] : '-') . '</td>
    <td><a href="https://bugs.php.net/bug.php?id=' . $bugfix['bugs'] . '">#' . $bugfix['bugs'] . '</a></td>
    <td>' . $cve . '</td>
                </tr>';
            } else {
                continue; // ignore. Possibly some mis-configuration
            }
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'BUG_FIXES', $table);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generatePhpConfiguration(Section $section): void {
        $phpConfiguration = new Phpcompilation();
        $report = $phpConfiguration->generate('', self::INLINE);

        $configline = trim($report);
        $configline = str_replace(array(' ', "\n") , array('&nbsp;', "<br />\n", ), $configline);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPILATION', $configline);
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->source, $html);
    }

    protected function generateCompatibilityEstimate(Section $section): void {
        $html = $this->getBasedPage($section->source);

        $versions = array('5.2', '5.3', '5.4', '5.5', '5.6', '7.0', '7.1', '7.2', '7.3', '7.4', '8.0');
        $scores = array_fill_keys(array_values($versions), 0);
        $versions = array_reverse($versions);

        $analyzers = array(
                            'Php/UseMatch'                          => '8.0+',
                            'Php/SignatureTrailingComma'            => '8.0+',
                            'Php/Php80OnlyTypeHints'                => '8.0+',
                            'Php/Php80UnionTypehint'                => '8.0+',
                            'Php/Php80VariableSyntax'               => '8.0+',
                            'Php/ThrowWasAnExpression'              => '8.0+',
                            'Php/Php80NewFunctions'                 => '8.0-',
                            'Php/Php80RemovedFunctions'             => '8.0-',
                            'Php/Php80RemovedConstant'              => '8.0-',

                            'Structures/toStringThrowsException'    => '7.4-',
                            'Php/NestedTernaryWithoutParenthesis'   => '7.4-',
                            'Php/TypedPropertyUsage'                => '7.4-',
                            'Php/UseCovariance'                     => '7.4-',
                            'Php/UseContravariance'                 => '7.4-',
                            'Php/Php74NewDirective'                 => '7.4-',
                            'Php/SpreadOperatorForArray'            => '7.4+',
                            'Php/UnpackingInsideArrays'             => '7.4+',
                            'Structures/CurlVersionNow'             => '7.4+',
                            'Php/Php74RemovedFunctions'             => '7.4+',
                            'Php/Php74Deprecation'                  => '7.4+',
                            'Php/Php74ReservedKeyword'              => '7.4+',
                            'Functions/UseArrowFunctions'           => '7.4+',
                            'Php/IntegerSeparatorUsage'             => '7.4+',
                            'Php/NoMoreCurlyArrays'                 => '7.4+',
                            'Php/CoalesceEqual'                     => '7.4+',
                            'Php/ConcatAndAddition'                 => '7.4+',

                            'Php/Php73NewFunctions'                 => '7.3-',
                            'Php/ListWithReference'                 => '7.3+',
                            'Constants/CaseInsensitiveConstants'    => '7.3+',
                            'Php/FlexibleHeredoc'                   => '7.3+',
                            'Php/PHP73LastEmptyArgument'            => '7.3+',

                            'Php/Php72Deprecation'                  => '7.2-',
                            'Php/Php72NewClasses'                   => '7.2-',
                            'Php/Php72NewConstants'                 => '7.2-',
                            'Php/Php72NewFunctions'                 => '7.2-',
                            'Php/Php72ObjectKeyword'                => '7.2-',
                            'Php/Php72RemovedFunctions'             => '7.2-',
                            'Classes/CantInheritAbstractMethod'     => '7.2+',
                            'Classes/ChildRemoveTypehint'           => '7.2+',
                            'Php/GroupUseTrailingComma'             => '7.2+',

                            'Php/Php71NewClasses'                   => '7.1-',
                            'Php/Php71NewFunctions'                 => '7.1-',
                            'Type/OctalInString'                    => '7.1-',
                            'Classes/ConstVisibilityUsage'          => '7.1+',
                            'Php/ListShortSyntax'                   => '7.1+',
                            'Php/ListWithKeys'                      => '7.1+',
                            'Php/Php71RemovedDirective'             => '7.1+',
                            'Php/UseNullableType'                   => '7.1+',

                            'Classes/AbstractStatic'                => '7.0-',
                            'Classes/NullOnNew'                     => '7.0-',
                            'Classes/UsingThisOutsideAClass'        => '7.0-',
                            'Extensions/Extapc'                     => '7.0-',
                            'Extensions/Extereg'                    => '7.0-',
                            'Extensions/Extmysql'                   => '7.0-',
                            'Functions/MultipleSameArguments'       => '7.0-',
                            'Php/EmptyList'                         => '7.0-',
                            'Php/ForeachDontChangePointer'          => '7.0-',
                            'Php/GlobalWithoutSimpleVariable'       => '7.0-',
                            'Php/NoListWithString'                  => '7.0-',
                            'Php/Php70NewClasses'                   => '7.0-',
                            'Php/Php70NewFunctions'                 => '7.0-',
                            'Php/Php70NewInterfaces'                => '7.0-',
                            'Php/Php70RemovedFunctions'             => '7.0-',
                            'Php/ReservedKeywords7'                 => '7.0-',
                            'Structures/BreakOutsideLoop'           => '7.0-',
                            'Structures/SwitchWithMultipleDefault'  => '7.0-',
                            'Type/MalformedOctal'                   => '7.0-',
                            'Structures/pregOptionE'                => '7.0-',
                            'Classes/Anonymous'                     => '7.0+',
                            'Extensions/Extast'                     => '7.0+',
                            'Extensions/Extzbarcode'                => '7.0+',
                            'Php/Coalesce'                          => '7.0+',
                            'Php/DeclareStrictType'                 => '7.0+',
                            'Php/DefineWithArray'                   => '7.0+',
                            'Php/NoStringWithAppend'                => '7.0+',
                            'Php/Php70RemovedDirective'             => '7.0+',
                            'Php/Php7RelaxedKeyword'                => '7.0+',
                            'Php/ReturnTypehintUsage'               => '7.0+',
                            'Php/ScalarTypehintUsage'               => '7.0+',
                            'Php/UnicodeEscapeSyntax'               => '7.0+',
                            'Php/UseSessionStartOptions'            => '7.0+',
                            'Php/YieldFromUsage'                    => '7.0+',
                            'Security/UnserializeSecondArg'         => '7.0+',
                            'Structures/IssetWithConstant'          => '7.0+',
                            'Php/ParenthesisAsParameter'            => '7.0+',

                            'Php/Php56NewFunctions'                 => '5.6-',
                            'Structures/CryptWithoutSalt'           => '5.6-',
                            'Namespaces/UseFunctionsConstants'      => '5.6+',
                            'Php/ConstantScalarExpression'          => '5.6+',
                            'Php/debugInfoUsage'                    => '5.6+',
                            'Php/EllipsisUsage'                     => '5.6+',
                            'Php/ExponentUsage'                     => '5.6+',
                            'Structures/ConstantScalarExpression'   => '5.6+',

                            'Php/Php55NewFunctions'                 => '5.5-',
                            'Php/Php55RemovedFunctions'             => '5.5-',
                            'Php/CantUseReturnValueInWriteContext'  => '5.5+',
                            'Php/ConstWithArray'                    => '5.5+',
                            'Php/Password55'                        => '5.5+',
                            'Php/StaticclassUsage'                  => '5.5+',
                            'Structures/ForeachWithList'            => '5.5+',
                            'Structures/EmptyWithExpression'        => '5.5+',
                            'Structures/TryFinally'                 => '5.5+',

                            'Php/ClosureThisSupport'                => '5.4-',
                            'Php/HashAlgos54'                       => '5.4-',
                            'Php/Php54RemovedFunctions'             => '5.4-',
                            'Structures/Break0'                     => '5.4-',
                            'Structures/BreakNonInteger'            => '5.4-',
                            'Structures/CalltimePassByReference'    => '5.4-',
                            'Php/MethodCallOnNew'                   => '5.4+',
                            'Type/Binary'                           => '5.4+',

                            'Php/Php54NewFunctions'                 => '5.3-',
                            'Structures/DereferencingAS'            => '5.3-',
                          );

//        $colors = array('7900E5', 'BB00E1', 'DD00BF', 'D9007B', 'D50039', 'D20700', 'CE4400', 'CA8000', 'C6B900', '95C200', '59BF00', );
//        $colors = array('7900E5', 'DD00BF', 'D50039', 'CE4400', 'C6B900', '59BF00');
        $colors = array('59BF00', '59BF00', '59BF00', 'BEC500', 'CB6C00', 'D20700', 'D80064', 'DE00D7', '7900E5', '7900E5');
        // This must be the same lenght than the list of versions

        $results = $this->dump->fetchAnalysersCounts(array_keys($analyzers));
        $counts = $results->toHash('analyzer', 'count');

        $data = array();
        $data2 = array();
        foreach($analyzers as $analyzer => $analyzerVersion) {
            $coeff = $analyzerVersion[-1] === '+' ? 1 : -1;

            foreach($versions as $version) {
                if (!isset($counts[$analyzer])) {
                    $data2[$analyzer][$version] = '<i class="fa fa-eye-slash" style="color: #dddddd"></i>';
                } elseif ($counts[$analyzer] === 0) {
                    $data2[$analyzer][$version] = '<i class="fa fa-eye-slash" style="color: #dddddd"></i>';
                } elseif ($coeff * version_compare($version, $analyzerVersion) >= 0) {
                    $data[$analyzer][$version] = '<i class="fa fa-check-square-o" style="color: seagreen"></i>';
                    ++$scores[$version];
                } else {
                    $data[$analyzer][$version] = '<i class="fa fa-warning" style="color: crimson"></i>';
                }
            }
        }

        $incompilable = array();
        foreach($versions as $version) {
            $shortVersion = $version[0] . $version[2];

            $res = $this->dump->fetchHash("notCompilable$shortVersion")->toString();
            if ($res === 'N/C') {
                $incompilable[$shortVersion] = '<i class="fa fa-eye-slash" style="color: #dddddd"></i>';
                continue;
            }

            $results = $this->dump->fetchTable("compilation$shortVersion");
            // -1 is for no result found.
            if ($results->getCount() <= 0) {
                $incompilable[$shortVersion] = '<i class="fa fa-check-square-o" style="color: seagreen"></i>';
            } else {
                $incompilable[$shortVersion] = '<i class="fa fa-warning" style="color: crimson"></i>';
            }
        }

        $table = array();
        $titles = '<tr><th>Version</th><th>Name</th><th>' . implode('</th><th>', array_keys(array_values($data2)[0]) ) . '</th></tr>';
        $table []= '<tr><td>&nbsp;</td><td>Compilation</td><td>' . implode('</td><td>', $incompilable) . "</td></tr>\n";
        $data = array_merge($data, $data2);
        foreach($data as $name => $row) {
            $analyzer = $this->rulesets->getInstance($name, null, $this->config);
            if ($analyzer === null) {
                continue;
            }

            $link = '<a href="analyses_doc.html#' . $this->toId($name) . '" alt="Documentation for ' . $name . '"><i class="fa fa-book"></i></a>';

            $color = $colors[array_search(rtrim($analyzers[$name], '+-'), $versions)];
            $table []= "<tr><td style=\"background-color: #{$color};\">$analyzers[$name]</td><td>$link {$this->docs->getDocs($name, 'name')}</td><td>" . implode('</td><td>', $row) . "</td></tr>\n";
        }

        $table = implode('', $table);

        $theTable = <<<HTML
        					<table class="table table-striped">
        						<tr></tr>
        						$titles
        						$table
        					</table>
HTML;

        $max = max($scores);
        $key = array_keys($scores, $max);

        if ($max === count($data)) {
            $suggestion = 'This code is compatible with PHP ' . implode(', ', $key);
        } else {
            $suggestion = 'We have determined ' . count($key) . ' PHP version' . (count($key) > 1 ? 's' : '') . '. The compatible estimations are PHP ' . implode(', ', $key) . '. ';
        }

        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', $suggestion);
        $html = $this->injectBloc($html, 'CONTENT', $theTable);

        $this->putBasedPage($section->file, $html);
    }

    protected function generateAuditConfig(Section $section): void {
        $config = new DatastoreConfig();
        $ini  = $config->toIni();
        $yaml = $config->toYaml();

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'CONFIG_INI', $ini);
        $html = $this->injectBloc($html, 'CONFIG_YAML', $yaml);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateAnalyzerSettings(Section $section): void {
        $settings = '';

        $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/' . $this->config->project_packagist . '">' . $this->config->project_packagist . '</a>');
        }
        $info = array_merge($info, $this->getVCSInfo());

        $info[] = array('Number of PHP files', $this->dump->fetchHash('files')->toString());
        $info[] = array('Number of lines of code', $this->dump->fetchHash('loc')->toString());
        $info[] = array('Number of lines of code with comments', $this->dump->fetchHash('locTotal')->toString());

        $info[] = array('Analysis execution date', date('r', $this->dump->fetchHash('audit_end')->toInt()));
        $info[] = array('Analysis runtime', duration($this->dump->fetchHash('audit_end')->toString() - $this->dump->fetchHash('audit_start')->toString()));
        $info[] = array('Report production date', date('r', time()));

        $php = exakat('php');
        $info[] = array('PHP used', $this->config->phpversion . ' (' . $php->getConfiguration('phpversion') . ')');

        $info[] = array('Exakat version', $this->dump->fetchHash('exakat_version')->toString() . ' ( Build ' . $this->dump->fetchHash('exakat_build')->toString() . ') ');
        $list = $this->config->ext->getPharList();
        $html = array();
        foreach(array_keys($list) as $name) {
            $html[] = '<li>' . basename($name, '.phar') . '</li>';
        }
        $info[] = array('Exakat modules', '<ul>' . implode(PHP_EOL, $html) . '</ul>');

        foreach($info as &$row) {
            $row = '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
        }
        unset($row);

        $settings = implode(PHP_EOL, $info);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateErrorMessages(Section $section): void {
        $errorMessages = '';

        $results = $this->dump->fetchAnalysers(array('Structures/ErrorMessages'));

        foreach($results->toArray() as $row) {
            $errorMessages .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'ERROR_MESSAGES', $errorMessages);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateExternalServices(Section $section): void {
        $externalServices = array();

        $res = $this->dump->fetchTable('configFiles')->toArray();
        foreach($res as $row) {
            if (empty($row['homepage'])) {
                $link = '';
            } else {
                $link = '<a href="' . $row['homepage'] . '">' . $row['homepage'] . '&nbsp;<i class="fa fa-sign-out"></i></a>';
            }

            $externalServices []= "<tr><td>$row[name]</td><td>$row[file]</td><td>$link</td></tr>";
        }
        $externalServices = implode(PHP_EOL, $externalServices);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'EXTERNAL_SERVICES', $externalServices);
        $html = $this->injectBloc($html, 'TTLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateDirectiveList(Section $section): void {
        // @todo automate this : Each string must be found in Report/Content/Directives/*.php and vice-versa
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

        $directiveList = '';
        // Possibly move this to a specific ruleset
        $list = $this->rulesets->getRulesetsAnalyzers(array('Appinfo'));
        $res = $this->dump->fetchAnalysersCounts($list);

        foreach($res->toArray() as $row) {
            $data = array();

            if ($row['analyzer'] === 'Structures/FileUploadUsage' && $row['count'] !== 0) {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>File Upload</td></tr>\n";
                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/fileupload.json"));
            } elseif ($row['analyzer'] === 'Php/UsesEnv' && $row['count'] !== 0) {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Environment</td></tr>\n";
                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/env.json"));
            } elseif ($row['analyzer'] === 'Php/UseBrowscap' && $row['count'] !== 0) {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Browser</td></tr>\n";
                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/browscap.json"));
            } elseif ($row['analyzer'] === 'Php/DlUsage' && $row['count'] === 0) {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Enable DL</td></tr>\n";
                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/enable_dl.json"));
            } elseif ($row['analyzer'] === 'Php/ErrorLogUsage' && $row['count'] !== 0) {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Error Log</td></tr>\n";
                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/errorlog.json"));
            } elseif ($row['analyzer'] === 'Security/CantDisableFunction' ||
                      $row['analyzer'] === 'Security/CantDisableClass'
                      ) {
                $list = $this->dump->getFunctionsFromAnalyzer('Security/CantDisableFunction');

                if (isset($disable)) {
                    continue;
                }
                $disable = parse_ini_file("{$this->config->dir_root}/data/disable_functions.ini");
                $suggestions = array_diff($disable['disable_functions'], $list);

                $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/disable_functions.json"));

                // disable_functions
                $data[0]->suggested = implode(', ', $suggestions);
                $data[0]->documentation .= "\n; " . count($list) . " sensitive functions were found in the code. Don't disable those : " . implode(', ', $list);

                $list = $this->dump->getFunctionsFromAnalyzer('Security/CantDisableClass');
                $suggestions = array_diff($disable['disable_classes'], $list);

                // disable_functions
                $data[1]->suggested = implode(',', $suggestions);
                $data[1]->documentation .= "\n; " . count($list) . " sensitive classes were found in the code. Don't disable those : " . implode(', ', $list);
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Disable features</td></tr>\n";
            } elseif ($row['count'] !== 0) {
                $ext = substr($row['analyzer'], 14);
                if (in_array($ext, $directives, \STRICT_COMPARISON)) {
                    $data = json_decode(file_get_contents("{$this->config->dir_root}/data/directives/$ext.json"));
                    $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>$ext</td></tr>\n";
                }
            }

            foreach($data as $directive) {
                $directiveList .= "<tr><td>{$directive->name}</td><td>{$directive->suggested}</td><td>{$directive->documentation}</td></tr>\n";
            }
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'DIRECTIVE_LIST', $directiveList);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateCompilations(Section $section): void {
        $compilations = array();

        $total = $this->dump->fetchHash('files')->toInt();

        foreach(array_unique(array_merge(array($this->config->phpversion[0] . $this->config->phpversion[2]), $this->config->other_php_versions)) as $suffix) {
            $suffix = (string) $suffix;
            $res = $this->dump->fetchHash("compilation$suffix");
            if ($res->getCount() === -1) {
                $version = $suffix[0] . '.' . $suffix[1];
                $compilations []= "<tr><td>$version</td><td>N/A</td><td>N/A</td><td>Compilation not tested</td><td>N/A</td></tr>";
                continue; // Table was not created
            }

            $res = $this->dump->fetchTable("compilation$suffix");
            $files = $res->getColumn('file');

            if (empty($files)) {
                $files       = 'No compilation error found.';
                $errors      = 'N/A';
                $total_error = 'N/A';
            } else {
                $readErrors = $res->getColumn('error');

                $errors      = array_count_values($readErrors);
                $errors      = array_keys($errors);
                $errors      = array_keys(array_count_values($errors));
                $errors      = '<ul><li>' . implode("</li>\n<li>", $errors) . '</li></ul>';

                $total_error = count($files) . ' (' . number_format(count($files) / $total * 100, 0) . '%)';
                $files       = array_keys(array_count_values($files));
                $files       = '<ul><li>' . implode("</li>\n<li>", $files) . '</li></ul>';
            }

            $version = $suffix[0] . '.' . $suffix[1];
            $compilations []= "<tr><td>$version</td><td>$total</td><td>$total_error</td><td>$files</td><td>$errors</td></tr>";
        }

        $compilations = implode(PHP_EOL, $compilations);
        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPILATIONS', $compilations);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateCompatibility80(Section $section): void {
        $this->generateCompatibility($section, '80');
    }

    protected function generateCompatibility74(Section $section): void {
        $this->generateCompatibility($section, '74');
    }

    protected function generateCompatibility73(Section $section): void {
        $this->generateCompatibility($section, '73');
    }

    protected function generateCompatibility72(Section $section): void {
        $this->generateCompatibility($section, '72');
    }

    protected function generateCompatibility71(Section $section): void {
        $this->generateCompatibility($section, '71');
    }

    protected function generateCompatibility70(Section $section): void {
        $this->generateCompatibility($section, '70');
    }

    protected function generateCompatibility56(Section $section): void {
        $this->generateCompatibility($section, '56');
    }

    protected function generateCompatibility55(Section $section): void {
        $this->generateCompatibility($section, '55');
    }

    protected function generateCompatibility54(Section $section): void {
        $this->generateCompatibility($section, '54');
    }

    protected function generateCompatibility53(Section $section): void {
        $this->generateCompatibility($section, '53');
    }

    protected function generateCompatibility(Section $section, string $version): void {
        $compatibility = array();
        $skipped       = array();

        $list = $this->rulesets->getRulesetsAnalyzers(array('CompatibilityPHP' . $version));

        $res = $this->dump->fetchAnalysersCounts($list);
        $counts = $res->toHash('analyzer', 'count');

        foreach($list as $analyzer) {
            $ini = $this->docs->getDocs($analyzer);
            if (isset($counts[$analyzer])) {
                $resultState = (int) $counts[$analyzer];
            } else {
                $resultState = -2; // -2 === not run
            }
            $result = $this->Compatibility($resultState, $analyzer);
            $link = '<a href="analyses_doc.html#' . $this->toId($analyzer) . '" alt="Documentation for ' . $ini['name'] . '"><i class="fa fa-book"></i></a>';
            if ($resultState === Analyzer::VERSION_INCOMPATIBLE) {
                $skipped []= "<tr><td>$link {$ini['name']}</td><td>$result</td></tr>\n";
            } else {
                $compatibility []= "<tr><td>$link {$ini['name']}</td><td>$result</td></tr>\n";
            }
        }
        $compatibility = implode(PHP_EOL, $compatibility) . PHP_EOL . implode(PHP_EOL, $skipped);

        $description = <<<'HTML'
<i class="fa fa-check-square-o"></i> : Nothing found for this analysis, proceed with caution; <i class="fa fa-warning red"></i> : some issues found, check this; <i class="fa fa-ban"></i> : Can't test this, PHP version incompatible; <i class="fa fa-cogs"></i> : Can't test this, PHP configuration incompatible; 
HTML;

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPATIBILITY', $compatibility);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', $description);
        $this->putBasedPage($section->file, $html);
    }

    private function generateDynamicCode(Section $section): void {
        $dynamicCode = '';

        $results = $this->dump->fetchAnalysers(array('Structures/DynamicCode'));

        foreach($results->toArray() as $row) {
            $dynamicCode .= "<tr><td>{$row['htmlcode']}</td><td>{$row['file']}</td><td>{$row['line']}</td></tr>\n";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'DYNAMIC_CODE', $dynamicCode);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateGlobals(Section $section): void {
        $res = $this->dump->fetchTable('globalVariables');

        if ($res->isEmpty()) {
            $this->emptyResult($section);
            return;
        }

        $tree = array();
        foreach($res->toArray() as $row) {
            $variable = trim($row['variable'], '{}&@');
            $name = preg_replace('/^\$GLOBALS\[[ \'"]*(.*?)[ \'"]*\]$/', '$\1', $variable);
            if (substr($variable, 0, 8) === '$GLOBALS') {
                $origin = '$GLOBALS';
            } else {
                $origin = 'global';
            }

            if (isset($tree[$name])) {
                ++$tree[$name]['count'];
                $tree[$name]['file'][]       = $row['file'] . ':' . $row['line'];
                $tree[$name]['type'][]       = $row['type'];
                $tree[$name]['status'][]     = ($row['isRead'] ? 'R' : '&nbsp;' ) . ' - ' . ($row['isModified'] ? 'W' : '&nbsp;' );
            } else {
                $tree[$name]['count']      = 1;
                $tree[$name]['file']       = array($row['file'] . ':' . $row['line']        );
                $tree[$name]['type']       = array($row['type']);
                $tree[$name]['status']     = array(($row['isRead'] ? 'R' : '&nbsp;' ) . ' - ' . ($row['isModified'] ? 'W' : '&nbsp;' ));
            }
        }

        uasort($tree, function (array $a, array $b): int { return $a['count'] <=> $b['count'];});

        $theGlobals = array();
        foreach($tree as $variable => $details) {
            $count      = $details['count'];
            $types      = implode('<br />', $details['type']);
            $status     = implode('<br />', $details['status']);
            $files      = implode('<br />', $details['file']);

            $theGlobals []= "<tr><td><span style=\"color: #0000BB\">$variable</span></td><td>$count</td><td>$types</td><td>$status</td><td>$files</td></tr>\n";
        }
        $theGlobals = implode('', $theGlobals);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'GLOBALS', $theGlobals);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateInventoriesConstants(Section $section): void {
        $this->generateInventories($section, array('Constants/Constantnames'), 'List of all defined constants in the code.');
    }

    private function generateInventoriesClasses(Section $section): void {
        $this->generateInventories($section, array('Classes/Classnames'), 'List of all defined classes in the code.');
    }

    private function generateInventoriesInterfaces(Section $section): void {
        $this->generateInventories($section, array('Interfaces/Interfacenames'), 'List of all defined interfaces in the code.');
    }

    private function generateInventoriesTraits(Section $section): void {
        $this->generateInventories($section, array('Traits/Traitnames'), 'List of all defined traits in the code.');
    }

    private function generateInventoriesFunctions(Section $section): void {
        $this->generateInventories($section, array('Functions/Functionnames'), 'List of all defined functions in the code.');
    }

    private function generateInventoriesNamespaces(Section $section): void {
        $this->generateInventories($section, array('Namespaces/Namespacesnames'), 'List of all defined namespaces in the code.');
    }

    private function generateInventoriesUrl(Section $section): void {
        $this->generateInventories($section, array('Type/Url'), 'List of all URL mentioned in the code.');
    }

    private function generateInventoriesRegex(Section $section): void {
        $this->generateInventories($section, array('Type/Regex'), 'List of all Regex mentioned in the code.');
    }

    private function generateInventoriesSql(Section $section): void {
        $this->generateInventories($section, array('Type/Sql'), 'List of all SQL mentioned in the code.');
    }

    private function generateInventoriesGPCIndex(Section $section): void {
        $this->generateInventories($section, array('Type/GPCIndex'), 'List of all Email mentioned in the code.');
    }

    private function generateInventoriesEmail(Section $section): void {
        $this->generateInventories($section, array('Type/Email'), 'List of all incoming variables mentioned in the code.');
    }

    private function generateInventoriesMd5string(Section $section): void {
        $this->generateInventories($section, array('Type/Md5string'), 'List of all MD5-like strings mentioned in the code.');
    }

    private function generateInventoriesMime(Section $section): void {
        $this->generateInventories($section, array('Type/MimeType'), 'List of all Mime strings mentioned in the code.');
    }

    private function generateInventoriesPack(Section $section): void {
        $this->generateInventories($section, array('Type/Pack'), 'List of all packing format strings mentioned in the code.');
    }

    private function generateInventoriesPrintf(Section $section): void {
        $this->generateInventories($section, array('Type/Printf'), 'List of all printf(), sprintf(), etc. formats strings mentioned in the code.');
    }

    private function generateInventoriesPath(Section $section): void {
        $this->generateInventories($section, array('Type/Path'), 'List of all paths strings mentioned in the code.');
    }

    private function generateInventories(Section $section, array $analyzer, string $description): void {
       $results = $this->dump->fetchAnalysers($analyzer);

       $groups = array();
       foreach($results->toArray() as $row) {
           $groups[$row['htmlcode']][] = $row['file'];
       }
       uasort($groups, function (array $a, array $b): int { return count($a) <=> count($b);});

       $theTable = array();
       foreach($groups as $code => $list) {
           $c = count($list);
           $htmlList = '<ul><li>' . implode('</li><li>', $list) . '</li></ul>';
           $theTable []= "<tr><td>{$code}</td><td>$c</td><td>{$htmlList}</td></tr>";
       }

       $html = $this->getBasedPage($section->source);
       $html = $this->injectBloc($html, 'TITLE', $section->title);
       $html = $this->injectBloc($html, 'DESCRIPTION', $description);
       $html = $this->injectBloc($html, 'TABLE', implode(PHP_EOL, $theTable));
       $this->putBasedPage($section->file, $html);
    }

    private function generateInterfaceTree(Section $section): void {
        $res = $this->dump->getCitTree('interface');
        foreach($res->toArray() as $row) {
            if (empty($row['parent'])) {
                continue;
            }

            $parent = $row['parent'];
            if (!isset($list[$parent])) {
                $list[$parent] = array();
            }

            $list[$parent][] = $row['child'];
        }

        if (empty($list)) {
            $theTable = 'No interface were found in this repository.';
        } else {
            array_sub_sort($list);

            $secondaries = array_merge(...array_values($list));
            $top = array_diff(array_keys($list), $secondaries);

            $theTableArray = array();
            foreach($top as $t) {
                $theTableArray[] = '<ul class="tree">' . $this->extends2ul($t, $list) . '</ul>';
            }
            $theTable = implode(PHP_EOL, $theTableArray);
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the interface trees : the interfaces that are extended by another interface. Interface without extension are not represented here');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);

    }

    private function generateConstantTree(Section $section): void {
        $list = array();
        $res = $this->dump->fetchTable('constantOrder');
        foreach($res->toArray() as $row) {
            if (empty($row['built'])) {
                continue;
            }

            $built = $row['built'];
            if (!isset($list[$built])) {
                $list[$built] = array();
            }

            $list[$built][] = $row['building'];
        }

        if (empty($list)) {
            $theTable = 'No structured constant were found in this repository.';
        } else {
            array_sub_sort($list);

            $secondaries = array_merge(...array_values($list));
            $top = array_diff(array_keys($list), $secondaries);

            $theTableArray = array();
            foreach($top as $t) {
                $theTableArray[] = '<ul class="tree">' . $this->extends2ul($t, $list) . '</ul>';
            }
            $theTable = implode(PHP_EOL, $theTableArray);
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the constant trees : a constant (global or class) is build based on another constant. Constants built only with literals are not represented here.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);

    }

    private function generateTraitMatrix(Section $section): void {
        // list of all traits, for building the table
        $traits = $this->dump->getCit('trait')->getColumn('name');

        // INIT
        $table = array_fill_keys($traits, array_fill_keys($traits, array()));

        // Get conflicts
        $res = $this->dump->getTraitConflicts();
        foreach($res->toArray() as $row) {
            $table[$row['t1']][$row['t2']][] = $row['method'];
        }

        // Get trait usage
        $res = $this->dump->getTraitUsage();
        $usage = $res->toHash('t1', 't2');

        $rows = array();
        foreach($table as $name => $row) {
            $cells = array();
            foreach($row as $t2 => $r) {
                $content = empty($r) ? '&nbsp;' : implode('(), ', $r) . '()';
                $background = isset($usage[$name][$t2]) ? ' bgcolor="darkgray"' : '';

                $cells[] = "<td$background>$content</td>";
            }
            $cells = implode('', $cells);
            $rows[] = "<tr><td>$name</td>$cells</tr>\n";
        }

        $cells = implode('</td><td>', $traits);
        $rows = implode('', $rows);
        $theTable = <<<HTML
<table class="table table-striped">
    <tr>
        <td>&nbsp;</td>
        <td>$cells</td>
    </tr>
    $rows
</table>
HTML;

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the trait matrix. Conflicting methods between any two traits are listed in the cells : when they are used in the same class, those traits will require conflict resolutions. And dark gray cells are traits that are actually included one into the other.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function generateTraitTree(Section $section): void {
        $list = array();

        $res = $this->dump->getCitTree('trait');
        foreach($res->toArray() as $row) {
            if (empty($row['child'])) {
                continue;
            }

            $parent = $row['child'];
            if (!isset($list[$parent])) {
                $list[$parent] = array();
            }

            $list[$parent][] = $row['parent'];
        }

        if (empty($list)) {
            $theTable = 'No trait were found in this repository.';
        } else {
            array_sub_sort($list);

            $theTable = array();
            foreach(array_keys($list) as $t) {
                $theTable[] = '<ul class="tree">' . $this->extends2ul($t, $list) . '</ul>';
            }
            $theTable = implode(PHP_EOL, $theTable);
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the extension trees of the traits : a trait is extended when it uses another trait. Traits without any extension are not represented. The same trait may be mentionned several times, as trait may use an arbitrary number of traits.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function generateClassTree(Section $section): void {
        $list = array();

        $res = $this->dump->getCitTree('class');
        foreach($res->toArray() as $row) {
            if (empty($row['parent'])) {
                continue;
            }

            $parent = $row['parent'];
            if (!isset($list[$parent])) {
                $list[$parent] = array();
            }

            $list[$parent][] = $row['child'];
        }

        if (empty($list)) {
            $theTable = 'No class were found in this repository.';
        } else {
            array_sub_sort($list);

            $secondaries = array_merge(...array_values($list));
            $top = array_diff(array_keys($list), $secondaries);

            $theTable = array();
            foreach($top as $t) {
                $theTable[] = '<ul class="tree">' . $this->extends2ul($t, $list) . '</ul>';
            }
            $theTable = implode(PHP_EOL, $theTable);
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', 'Classes Tree');
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the classes tree, built with class extensions. Classes without any extension are not represented.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function extends2ul(string $root, array $paths, int $level = 0): string {
        static $done = array();

        if ($level === 0) {
            $done = array();
        }

        $return = array();
        foreach($paths[$root] as $sub) {
            if (isset($paths[$sub])){
                if (!isset($done[$sub]) && $level < 10) {
                    $done[$sub] = 1;
                    $secondary = $this->extends2ul($sub, $paths, $level + 1);
                    $return[] = $secondary;
                } else {
                    $return[] = '<li>' . $sub . '...(Recursive)</li>';
                }
            } else {
                $return[] = "<li class=\"treeLeaf\">$sub</li>";
                $done[$sub] = 1;
            }
        }
        $return = "<li>$root<ul>" . implode('', $return) . "</ul></li>\n";

        return $return;
    }

    private function generateExceptionTree(Section $section): void {
        $exceptions = array (
  'Throwable' =>
  array (
    'Error' =>
    array (
      'ParseError' =>
      array (
      ),
      'TypeError' =>
      array (
        'ArgumentCountError' =>
        array (
        ),
      ),
      'ArithmeticError' =>
      array (
        'DivisionByZeroError' =>
        array (
        ),
      ),
      'AssertionError' =>
      array (
      ),
    ),
    'Exception' =>
    array (
      'ErrorException' =>
      array (
      ),
      'ClosedGeneratorException' =>
      array (
      ),
      'DOMException' =>
      array (
      ),
      'LogicException' =>
      array (
        'BadFunctionCallException' =>
        array (
          'BadMethodCallException' =>
          array (
          ),
        ),
        'DomainException' =>
        array (
        ),
        'InvalidArgumentException' =>
        array (
        ),
        'LengthException' =>
        array (
        ),
        'OutOfRangeException' =>
        array (
        ),
      ),
      'RuntimeException' =>
      array (
        'OutOfBoundsException' =>
        array (
        ),
        'OverflowException' =>
        array (
        ),
        'RangeException' =>
        array (
        ),
        'UnderflowException' =>
        array (
        ),
        'UnexpectedValueException' =>
        array (
        ),
        'PDOException' =>
        array (
        ),
      ),
      'PharException' =>
      array (
      ),
      'ReflectionException' =>
      array (
      ),
    ),
  ),
);
        $list = array();

        $theTable = '';
        $res = $this->dump->fetchAnalysers(array('Exceptions/DefinedExceptions'));
        foreach($res->toArray() as $row) {
            if (!preg_match('/ extends (\S+)/', $row['fullcode'], $r)) {
                continue;
            }
            $parent = strtolower($r[1]);
            if ($parent[0] !== '\\') {
                $parent = "\\$parent";
            }

            if (!isset($list[$parent])) {
                $list[$parent] = array();
            }

            $list[$parent][] = $row['fullcode'];
        }

        array_sub_sort($list);
        $theTable = $this->tree2ul($exceptions, $list);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', '');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function path2tree(array $paths): array {
        $return = array();

        $recursive = array();
        foreach($paths as $path) {
            $folders = explode('\\', $path);

            $first = empty($folders[0]) ? '\\' : $folders[0];

            if (!isset($return[$first])) {
                $return[$first] = array();
            }

            if (count($folders) > 2) {
                $recursive[$first] = 1;
            }
            $return[$first][] = implode('\\', array_slice($folders, 1));
        }

        foreach(array_keys($recursive) as $recurrent) {
            $return[$recurrent] = $this->path2tree($return[$recurrent]);
        }

        return $return;
    }

    private function pathtree2ul(array $path): string {
        if (empty($path)) {
            return '';
        }
        $return = array('<ul>');

        foreach($path as $k => $v) {
            $return []= '<li>';

            if (is_string($v)) {
                if (empty($v)) {
                    $return []= '<div style="font-weight: bold">\\</div>';
                } else {
                    $return []= '<div style="font-weight: bold">' . $v . '</div>';
                }
            } elseif (count($v) === 1) {
                if (empty($v[0])) {
                    if (empty($k)) {
                        $return []= '<div style="font-weight: bold">\\</div>';
                    } else {
                        $return []= '<div style="font-weight: bold">' . $k . '</div>';
                    }
                } else {
                    $return []= '<div style="font-weight: bold">' . $k . '</div>' . $this->pathtree2ul($v);
                }
            } else {
                $return []= '<div style="font-weight: bold">' . $k . '</div>' . $this->pathtree2ul($v);
            }

            $return []= '</li>';
        }
        $return []= '</ul>';

        return implode('', $return);
    }

    private function generateNamespaceTree(Section $section): void {
        $theTable = '';
        $res = $this->dump->fetchTable('namespaces');
        $res->order(function (array $a, array $b): int { return $a['namespace'] <=> $b['namespace'];});
                $res->map(function (array $x): array { $x['namespace'] = trim($x['namespace'], '\\'); return $x;});
        $paths = $res->getColumn('namespace');

        $paths = $this->path2tree($paths);
        $theTable = $this->pathtree2ul($paths);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Here are the various namespaces in use in the code.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function tree2ul(array $tree,array $display): string {
        if (empty($tree)) {
            return '';
        }
        $return = '<ul>';

        foreach($tree as $k => $v) {
            $return .= '<li>';

            $parent = '\\' . strtolower((string) $k);
            if (isset($display[$parent])) {
                $return .= '<div style="font-weight: bold">' . $k . '</div><ul><li>' . implode('</li><li>', $display[$parent]) . '</li></ul>';
            } else {
                $return .= '<div style="font-weight: bold; color: darkgray">' . $k . '</div>';
            }

            if (is_array($v)) {
                $return .= $this->tree2ul($v, $display);
            }

            $return .= '</li>';
        }

        $return .= '</ul>';

        return $return;
    }

    private function generateVisibilitySuggestions(Section $section): void {
        $constants  = $this->generateVisibilityConstantSuggestions();
        $properties = $this->generateVisibilityPropertySuggestions();
        $methods    = $this->generateVisibilityMethodsSuggestions();

        $classes = array_unique(array_merge(array_keys($constants),
                                            array_keys($properties),
                                            array_keys($methods)));

        $visibilityTable = array(<<<'HTML'
<table class="table table-striped">
    <tr>
        <td>&nbsp;</td>
        <td>Name</td>
        <td>Value</td>
        <td>None (public)</td>
        <td>Public</td>
        <td>Protected</td>
        <td>Private</td>
        <td>Constant</td>
    </tr>
HTML
);

        foreach($classes as $id) {
            list(, $class) = explode(':', $id);
            $visibilityTable []= '<tr><td colspan="9">class ' . PHPsyntax($class) . '</td></tr>' . PHP_EOL .
                                (isset($constants[$id]) ? implode('', $constants[$id]) : '') .
                                (isset($properties[$id]) ? implode('', $properties[$id]) : '') .
                                (isset($methods[$id]) ? implode('', $methods[$id]) : '');
        }

        $visibilityTable []= '</table>';
        $visibilityTable = implode(PHP_EOL, $visibilityTable);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Below, is a summary of all classes and their component\'s visiblity. Whenever a visibility is set and used at the right level, a green star is presented. Whenever it is set to a level, but could be updated to another, red and orange stars are mentioned. ');
        $html = $this->injectBloc($html, 'CONTENT', $visibilityTable);
        $this->putBasedPage($section->file, $html);
    }

    private function generateClassOptionSuggestions(Section $section): void {
        $finals  = $this->generateClassFinalSuggestions();
        $abstracts = $this->generateClassAbstractuggestions();

        $classes = array_unique(array_merge($finals, $abstracts));

        $visibilityTable = array();
        foreach($classes as $path => $fullcode) {
            $class = str_replace('{ /**/ } ', '', $fullcode);

            if (isset($finals[$path])) {
                $final =  '<i class="fa fa-star" style="color:red"></i>';
            } elseif (stripos($fullcode, 'final') !== false) {
                $final =  '&nbsp';
            } else {
                $final =  '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($abstracts[$path])) {
                $abstract =  '<i class="fa fa-star" style="color:red"></i>';
            } elseif (stripos($fullcode, 'abstract') !== false) {
                $abstract =  '&nbsp';
            } else {
                $abstract =  '<i class="fa fa-star" style="color:green"></i>';
            }

            $visibilityTable[] = <<<HTML
<tr>
    <td colspan=\"9\">$final</td>
    <td colspan=\"9\">$abstract</td>
    <td colspan=\"9\">$class</td>
    <td colspan=\"9\">$path</td>
</tr>

HTML;
        }
        $visibilityTable = implode(PHP_EOL, $visibilityTable);

        $visibilityHtml = <<<HTML
<table class="table table-striped">
    <tr>
        <td>Final</td>
        <td>Abstract</td>
        <td>Name</td>
        <td>Path</td>
    </tr>
    $visibilityTable
    </table>
HTML;

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', <<<'HTML'
Below, is a list of classes that may be updated with final or abstract. <br />

The red stars <i class="fa fa-star" style="color:red"></i> mention possible upgrade by using final or abstract keywords; 
The green stars <i class="fa fa-star" style="color:green"></i> mention a valid absence of the option (an extended class, that can't be final, ...); 
The absence of star report currently configured classes.  

HTML
);
        $html = $this->injectBloc($html, 'CONTENT', $visibilityHtml);
        $this->putBasedPage($section->file, $html);
    }

    private function generateClassFinalSuggestions(): array {
        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeFinal'));

        $couldBeFinal = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['fullcode'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[2]);

            $couldBeFinal[$fullnspath] = $row['fullcode'];
        }

        return $couldBeFinal;
    }

    private function generateClassAbstractuggestions(): array {
        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeAbstractClass'));

        $couldBeAbstract = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['fullcode'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[2]);

            $couldBeAbstract[$fullnspath] = $row['fullcode'];
        }

        return $couldBeAbstract;
    }

    private function generateVisibilityMethodsSuggestions(): array {
        $res = $this->dump->fetchAnalysers(array('Classes/CouldBePrivateMethod'));

        $couldBePrivate = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['class'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[2]);

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $row['fullcode'];
            } else {
                $couldBePrivate[$fullnspath] = array($row['fullcode']);
            }
        }

        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeProtectedMethod'));
        $couldBeProtected = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/(class|interface|trait) (\S+) /i', $row['class'], $classname)) {
                continue;
            }
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[2]);

            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $row['fullcode'];
            } else {
                $couldBeProtected[$fullnspath] = array($row['fullcode']);
            }
        }

        $res = $this->dump->fetchTableMethods();
        $res->filter(function (array $x): bool { return $x['type'] === 'class'; });

        $ranking = array(''          => 0,
                         'none'      => 0,
                         'public'    => 1,
                         'protected' => 2,
                         'private'   => 3);

        $return = array();
        $theClass = '';
        $aClass = array();

        foreach($res->toArray() as $row) {
            if ($theClass != $row['fullnspath'] . ':' . $row['class']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'] . ':' . $row['class'];
                $aClass = array();
            }

            $visibilities = array('&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) &&
                in_array($row['method'], $couldBePrivate[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) &&
                in_array($row['method'], $couldBeProtected[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }

            $aClass[] = '<tr><td>&nbsp;</td><td>' . PHPSyntax($row['method']) . '</td><td class="exakat_short_text">' .
                                    implode('</td><td>', $visibilities)
                                 . '</td></tr>' . PHP_EOL;
        }

        $return[$theClass] = $aClass;
        unset($return['']);

        return $return;
    }

    private function generateVisibilityConstantSuggestions(): array {
        $res = $this->dump->fetchAnalysers(array('Classes/CouldBePrivateConstante'));

        $couldBePrivate = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/class (\S+) /i', $row['class'], $classname)) {
                continue; // it is an interface or a trait
            }

            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[1]);

            if (!preg_match('/^(.+) = /i', $row['fullcode'], $code)) {
                continue;
            }

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $code[1];
            } else {
                $couldBePrivate[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeProtectedConstant'));
        $couldBeProtected = array();
        foreach($res->toArray() as $row) {
            if (!preg_match('/class (\S+) /i', $row['class'], $classname)) {
                continue; // it is an interface or a trait
            }
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[1]);

            if (!preg_match('/^(.+) = /i', $row['fullcode'], $code)) {
                continue;
            }

            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $code[1];
            } else {
                $couldBeProtected[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->dump->fetchTableClassConstants();
        $res->filter(function (array $x): bool { return $x['type'] === 'class'; });

        $theClass = '';
        $ranking = array(''          => 1,
                         'public'    => 2,
                         'protected' => 3,
                         'private'   => 4,
                         'constant'  => 5);
        $return = array();

        $aClass = array();
        foreach($res->toArray() as $row) {
            if ($theClass != $row['fullnspath'] . ':' . $row['class']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'] . ':' . $row['class'];
                $aClass = array();
            }

            $visibilities = array(PHPSyntax((string) $row['value']), '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) &&
                in_array($row['constant'], $couldBePrivate[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) &&
                in_array($row['constant'], $couldBeProtected[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }

            $aClass[] = '<tr><td>&nbsp;</td><td>' . PHPSyntax($row['constant']) . '</td><td class="exakat_short_text">' .
                                    implode('</td><td>', $visibilities)
                                 . '</td></tr>' . PHP_EOL;
        }

        $return[$theClass] = $aClass;
        unset($return['']);

        return $return;
    }

    private function generateVisibilityPropertySuggestions(): array {

        $res = $this->dump->fetchAnalysers(array('Classes/CouldBePrivate'));
        $couldBePrivate = array();
        foreach($res->toArray() as $row) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            assert(isset($classname[1]), 'Missing class in ' . $row['class']);
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[2]);

            preg_match('/(\$\S+)/i', $row['fullcode'], $code);
            assert(isset($code[1]), 'Missing class in ' . $row['fullcode']);

            if (isset($couldBePrivate[$fullnspath])) {
                $couldBePrivate[$fullnspath][] = $code[1];
            } else {
                $couldBePrivate[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeProtectedProperty'));
        $couldBeProtected = array();
        foreach($res->toArray() as $row) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[1]);

            preg_match('/(\$\S+)/', $row['fullcode'], $code);

            if (isset($couldBeProtected[$fullnspath])) {
                $couldBeProtected[$fullnspath][] = $code[1];
            } else {
                $couldBeProtected[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->dump->fetchAnalysers(array('Classes/CouldBeClassConstant'));
        $couldBeConstant = array();
        foreach($res->toArray() as $row) {
            preg_match('/(class|trait) (\S+) /i', $row['class'], $classname);
            $fullnspath = $row['namespace'] . '\\' . strtolower($classname[1]);

            preg_match('/(\$\S+)/', $row['fullcode'], $code);

            if (isset($couldBeConstant[$fullnspath])) {
                $couldBeConstant[$fullnspath][] = $code[1];
            } else {
                $couldBeConstant[$fullnspath] = array($code[1]);
            }
        }

        $res = $this->dump->fetchTableProperty();
        $res->filter(function (array $x): bool { return $x['type'] === 'class'; });

        $theClass = '';
        $ranking = array(''          => 1,
                         'none'      => 1,
                         'public'    => 2,
                         'protected' => 3,
                         'private'   => 4,
                         'constant'  => 5);
        $return = array();

        $aClass = array();
        foreach($res->toArray() as $row) {
            if ($theClass != $row['fullnspath'] . ':' . $row['class']) {
                $return[$theClass] = $aClass;
                $theClass = $row['fullnspath'] . ':' . $row['class'];
                $aClass = array();
            }

            list($row['property']) = explode(' = ', $row['property'], 1);

            $visibilities = array(PHPSyntax($row['value']), '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
            $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:green"></i>';

            if (isset($couldBePrivate[$row['fullnspath']]) &&
                in_array($row['property'], $couldBePrivate[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['private']] = '<i class="fa fa-star" style="color:green"></i>';
            }

            if (isset($couldBeProtected[$row['fullnspath']]) &&
                in_array($row['property'], $couldBeProtected[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking[$row['visibility']]] = '<i class="fa fa-star" style="color:red"></i>';
                    $visibilities[$ranking['protected']] = '<i class="fa fa-star" style="color:#FFA700"></i>';
            }

            if (isset($couldBeConstant[$row['fullnspath']]) &&
                in_array($row['property'], $couldBeConstant[$row['fullnspath']], \STRICT_COMPARISON)) {
                    $visibilities[$ranking['constant']] = '<i class="fa fa-star" style="color:black"></i>';
            }

            $aClass[] = '<tr><td>&nbsp;</td><td>' . PHPSyntax($row['property']) . '</td><td class="exakat_short_text">' .
                            implode('</td><td>', $visibilities)
                            . '</td></tr>' . PHP_EOL;
        }
        $return[$theClass] = $aClass;
        unset($return['']);

        return $return;
    }

    private function generateAlteredDirectives(Section $section): void {
        $alteredDirectives = array();
        $res = $this->dump->fetchAnalysers(array('Php/DirectivesUsage'));
        foreach($res->toArray() as $row) {
            $alteredDirectives []= '<tr><td>' . PHPSyntax($row['fullcode']) . "</td><td>$row[file]</td><td>$row[line]</td></tr>";
        }
        $alteredDirectives = implode(PHP_EOL, $alteredDirectives);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'ALTERED_DIRECTIVES', $alteredDirectives);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateChangedClasses(Section $section): void {
        $changedClasses = '';
        $res = $this->dump->fetchTable('classChanges');

        if ($res->isEmpty() === true) {
            $changedClasses = 'No changes detected';
        } else {
            foreach($res->toArray() as $row) {
                if ($row['changeType'] === 'Member Visibility') {
                    $row['parentValue'] .= ' $' . $row['name'];
                    $row['childValue']   = ' $' . $row['name'];
                } elseif ($row['changeType'] === 'Member Default') {
                    $row['parentValue'] = '$' . $row['name'] . ' = ' . $row['parentValue'];
                    $row['childValue']  = '$' . $row['name'] . ' = ' . $row['childValue'];
                }

                $changedClasses .= '<tr><td>' . PHPSyntax($row['parentClass']) . '</td>' . PHP_EOL .
                                       '<td>' . PHPSyntax($row['parentValue']) . '</td>' . PHP_EOL .
                                       '</tr><tr>' .
                                       '<td>' . PHPSyntax($row['childClass']) . '</td>' . PHP_EOL .
                                       '<td>' . PHPSyntax($row['childValue']) . '</td>' . PHP_EOL .
                                       '</tr>' . PHP_EOL .
                                       '<tr><td colspan="2"><hr /></td></tr>';
            }
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'CHANGED_CLASSES', $changedClasses);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->source, $html);
    }

    private function emptyResult(Section $section): void {
        $finalHTML = $this->getBasedPage('empty');

        $finalHTML = $this->injectBloc($finalHTML, 'DESCRIPTION',  'No result were found for this analysis.');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'CONTENT', '');
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateParameterNames(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        $variables = $this->dump->fetchTable('variables')->getColumn('variable');
        $variables = array_flip($variables);

        $res = $this->dump->fetchTable('arguments');
        $parameters = $res->toGroupedCount('name');
        uasort($parameters, function ($a, $b): int { return $b <=> $a;});

        $typehints = array();
        foreach($res->toArray() as $row) {
            if (empty($row['typehint'])) { continue; }
            if (!isset($typehints[$row['name']])) {
                $typehints[$row['name']] = array($row['typehint']);

                continue;
            }

            $typehints[$row['name']][] = $row['typehint'];
        }
        $typehints = array_map('array_unique', $typehints);

        $defaults  = array();
        foreach($res->toArray() as $row) {
            if (empty($row['init'])) { continue; }
            if (!isset($defaults[$row['name']])) {
                $defaults[$row['name']] = array($row['init']);

                continue;
            }

            $defaults[$row['name']][] = $row['init'];
        }
        $defaults = array_map('array_unique', $defaults);

        $html = array();
        foreach ($parameters as $variable => $count) {
            $html []= '<tr>
                      <td>' . $variable . '</td>
                      <td>' . $count . '</td>
                      <td>' . (isset($variables[$variable]) ? 'X' : '') . '</td>
                      <td>' . (isset($typehints[$variable]) ? $this->toHtmlList($typehints[$variable]) : '') . '</td>
                      <td>' . (isset($defaults[$variable]) ? $this->toHtmlList($defaults[$variable]) : '') . '</td>
                  </tr>';
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'ANALYZERS', $html);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'CONTENT', '');
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateFossilizedMethods(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->fetchHashResults('FossilizedMethods');
        if ($res->isEmpty()) {
            $this->emptyResult($section);

            return ;
        }

        $html = array();
        $data = array();
        foreach ($res->order(function (array $a, array $b): int { return $b['value'] <=> $a['value']; })->toArray() as $value) {
            $data[$value['key'] . ' level' . ($value['key'] == 1 ? '' : 's')] = $value['value'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => 'Fossilized Methods', 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TYPE', 'Methods');

        $this->putBasedPage($section->file, $finalHTML);
    }

    private function generateClassDepth(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->fetchHashResults('Class Depth');
        if ($res->isEmpty()) {
            $this->emptyResult($section);

            return ;
        }

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
                $data[$value['key'] . ' level' . ($value['key'] == 1 ? '' : 's')] = $value['value'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => 'Depth', 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TYPE', 'Class');

        $this->putBasedPage($section->file, $finalHTML);
    }

    private function generateClassSize(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->getCitBySize();

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            if (count($data) < 50) {
                $data[$value['name']] = $value['size'];
            }

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['name'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['size'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => 'Class size (lines)', 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TYPE', 'Class');

        $this->putBasedPage($section->file, $finalHTML);
    }

    private function generateTypehintStats(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->fetchHashResults('Typehinting stats');

        $data = array('object' => 0);
        $total = 0;
        foreach ($res->toArray() as $value) {
            if (in_array($value['key'], array('totalArguments',
                                              'totalFunctions', ))) {
                $total += (int) $value['value'];
                continue;
            }

            if (in_array($value['key'], array('\\array',
                                               '\callable',
                                               '\\int',
                                               '\\string',
                                               '\\void',
                                               '\\iterable',
                                               '\\bool',
                                               '\\float',
                                              ))) {
                $data[$value['key']] = $value['value'];
                continue;
            }

        if (strpos($value['key'], '\\') !== false) {
            $data['object'] += $value['value'];
        }

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }

        $data['no type'] = $total - array_sum($data);
        arsort($data);

        $html = array();
        foreach($data as $name => $value) {
            $html []= <<<HTML
<div class="clearfix">
    <div class="block-cell-name">$name</div>
    <div class="block-cell-issue text-center">$value</div>
</div>

HTML;
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => 'Typehint stats',
                                    'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TYPE', 'Class');

        $this->putBasedPage($section->file, $finalHTML);
    }

    private function generateMethodSize(Section $section): void {
        $finalHTML = $this->getBasedPage($section->source);

        // List of extensions used
        $res = $this->dump->getMethodsBySize();
        $res->order(function (array $a, array $b): int { return $b['size'] <=> $a['size'];});

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            if (count($data) < 30) {
                $data[$value['name']] = $value['size'];
            }

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['name'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['size'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $finalHTML = $this->injectBloc($finalHTML, 'TOPFILE', $html);

        $highchart = new Highchart();
        $highchart->addSeries('filename',
                              array_keys($data),
                              array('name' => 'Method size', 'data' => array_values($data))
                              );
        $blocjs = (string) $highchart;

        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS',  $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TYPE', 'Method');

        $this->putBasedPage($section->file, $finalHTML);
    }

    private function generateStats(Section $section): void {
        $results = new Stats();
        $report = $results->generate('', self::INLINE);
        $report = json_decode($report);

        $stats = array();
        foreach($report as $group => $hash) {
            $stats []= "<tr><td colspan=2 bgcolor=\"#BBB\">$group</td></tr>";

            foreach($hash as $name => $count) {
                $stats []= "<tr><td>$name</td><td>$count</td></tr>";
            }
        }
        $stats = implode(PHP_EOL, $stats);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'STATS', $stats);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->source, $html);
    }

    private function generateComplexExpressions(Section $section): void {
        $results = $this->dump->fetchAnalysers(array('Structures/ComplexExpression'), array('phpsyntax' => array('fullcode' => 'htmlcode')));

        $expr = $results->getColumn('fullcode');
        $counts = array_count_values($expr);

        $expressions = '';
        foreach($results->toArray() as $row) {
            $expressions .= "<tr><td>{$row['file']}:{$row['line']}</td><td>{$counts[$row['fullcode']]}</td><td>{$row['htmlcode']}</td></tr>\n";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'BLOC-EXPRESSIONS', $expressions);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->source, $html);
    }

    protected function generateCodes(Section $section): void {
        $path = "{$this->tmpName}/data/sources";
        $pathToSource = dirname($this->tmpName) . '/code';
        mkdir($path, 0755);

        $filesList = $this->dump->fetchTable('files')->toArray();
        $files = '';
        $dirs = array('/' => 1);
        foreach($filesList as $row) {
            $subdirs = explode('/', trim(dirname($row['file']), '/'));
            $dir = '';
            foreach($subdirs as $subdir) {
                $dir .= "/$subdir";
                if (!isset($dirs[$dir])) {
                    mkdir($path . $dir, 0755);
                    $dirs[$dir] = 1;
                }
            }

            $sourcePath = "$pathToSource$row[file]";
            if (!file_exists($sourcePath)) {
                continue;
            }

            $id = str_replace('/', '_', $row['file']);
            $source = @highlight_file($sourcePath, \RETURN_VALUE);
            $files .= '<li><a href="#" id="' . $id . '" class="menuitem">' . makeHtml($row['file']) . "</a></li>\n";
            $source = substr($source, 6, -8);
            $source = preg_replace_callback('#<br />#is', function (array $x): string {
                static $i = 0;

                return '<br /><a name="l' . ++$i . '" />';
            }, $source);
            file_put_contents("$path$row[file]", $source);
        }

        $blocjs = <<<'JAVASCRIPT'
<script>
  "use strict";

  $('.menuitem').click(function(event){
    $('#results').load("sources/" + event.target.text);
    $('#filename').html(event.target.text + '  <span class="caret"></span>');
  });

  var fileParam = window.location.hash.split('file=')[1];
  if(fileParam !== undefined) {
    var limit = fileParam.indexOf('&');
    if (limit !== -1) {
        fileParam = fileParam.substr(0, limit);
    }
    $('#results').load("sources/" + fileParam);
    $('#filename').html(fileParam + '  <span class="caret"></span>');
  }

  var line = window.location.hash.split('line=')[1];
  if(line !== undefined) {
        window.location.hash = 'l' + line;
  }
  
  </script>
JAVASCRIPT;
        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'BLOC-JS', $blocjs);
        $html = $this->injectBloc($html, 'FILES', $files);
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $html);
    }

    private function generateFileDependencies(Section $section): void {
        $res = $this->dump->fetchTable('filesDependencies');
        $res->filter(function (array $x): bool { return ($x['included'] !== $x['including']) && in_array($x['type'], array('IMPLEMENTS', 'EXTENDS', 'INCLUDE', 'NEW'));});

        $nodes = array();
        foreach($res->toArray() as $row) {
            if (isset($nodes[$row['including']][$row['included']])) {
                $nodes[$row['including']][$row['included']] .= ', ' . $row['type'];
            } else {
                $nodes[$row['including']][$row['included']] = $row['type'];
            }
        }

        $next = array();
        foreach($nodes as $in => $out) {
            $set = false;

            foreach($next as $file => &$inc) {
                if ($file === $in) {
                    $inc = $out;
                    $set = true;
                }
            }

            if ($set === false) {
                $next[$in] = $out;
            }
        }
        unset($out);

        foreach($next as $in => &$out) {
            $out = array_keys($out);
            sort($out);
        }
        unset($out);

        if (empty($next)) {
            $secondaries = array();
        } else {
            $secondaries = array_merge(...array_values($next));
        }
        $top = array_diff(array_keys($next), $secondaries);

        $theTable = array();
        foreach($top as $t) {
            $theTable[] = '<ul class="tree">' . $this->extends2ul($t, $next) . '</ul>';
        }
        $theTable = implode(PHP_EOL, $theTable);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'This is the list of file dependencies. The top files require the bottom files to be included to be properly running. This dependency tree covers class usage : new, ::, extends and implements.');
        $html = $this->injectBloc($html, 'CONTENT', $theTable);
        $this->putBasedPage($section->file, $html);
    }

    private function generateIdenticalFiles(Section $section): void {
        $res = $this->dump->getIdenticalFiles();

        $theTable = array();
        foreach($res->toArray() as $row) {
            $list = str_replace(',', "</li>\n<li>", $row['list']);

            $theTable[] = <<<HTML
<tr>
    <td>$row[count]</td>
    <td>
        <ul>
            <li>$list</li>
        </ul>
    </td>
</tr>
HTML;
        }
        $theTable = implode(PHP_EOL, $theTable);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'IDENTICAL', $theTable);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateConcentratedIssues(Section $section): void {
        $list = $this->rulesets->getRulesetsAnalyzers(array('Analyze'));

        $res = $this->dump->getConcentratedIssues($list);

        $table = array();
        foreach($res->toArray() as list('line' => $line, 'file' => $file, 'count' => $count, 'list' => $list)) {
            $listHtml = array();
            foreach(explode(',', $list) as $l) {
                $listHtml[] = '<li>' . $this->makeDocLink($l) . '</li>';
            }
            $listHtml = '<ul>' . implode(PHP_EOL, $listHtml) . '</ul>';
            $table[] = "<tr><td>$file:$line</td><td>$count</td><td>$listHtml</td></tr>\n";
        }

        $table = implode(PHP_EOL, $table);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'BLOC-EXPRESSIONS', $table);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    private function generateConfusingVariables(Section $section): void {
        $data = new Data\CloseNaming($this->dump);
        $results = $data->prepare();
        $reasons = array('_'       => 'One _',
                         'numbers' => 'One digit',
                         'swap'    => 'Partial inversion',
                         'one'     => 'One letter',
                         'case'    => 'Case',
                         );

        $table = array();
        foreach($results as $variable => $close) {
            $confused = array();

            foreach($close as $reason => $variables) {
                $list = '<ul><li>' . implode('</li><li>', $variables) . "</li></ul>\n";
                $confused[] = "<tr><td>$list</td><td>{$reasons[$reason]}</td></tr>\n";
            }

            $count = count($close);
            $first = array_shift($confused);
            $table[] = str_replace('<tr>', "<tr><td rowspan=\"$count\">$variable</td>", $first) . PHP_EOL . implode('', $confused);
        }
        $table = implode(PHP_EOL, $table);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'CONTENT', $table);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function makeIcon(string $tag): string {
        switch($tag) {
            case self::YES :
                return '<i class="fa fa-check-square-o"></i>';
            case self::NO :
                return '<i class="fa fa-square-o"></i>';
            case self::NOT_RUN :
                return '<i class="fa fa-ban"></i>';
            case self::INCOMPATIBLE :
                return '<i class="fa fa-remove"></i>';
            default :
                return '&nbsp;';
        }
    }

    private function Bugfixes_cve(string $cve): string {
        if (empty($cve)) {
            return '-';
        }

        if (strpos($cve, ', ') === false) {
            $cveHtml = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=' . $cve . '">' . $cve . '</a>';
        } else {
            $cves = explode(', ', $cve);
            $cveHtml = array();
            foreach($cves as $cve) {
                $cveHtml[] = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name=' . $cve . '">' . $cve . '</a>';
            }
            $cveHtml = implode(',<br />', $cveHtml);
        }

        return $cveHtml;
    }

    protected function Compatibility(int $count, string $analyzer): string {
        if ($count === Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-ban" style="color: orange"></i>';
        } elseif ($count === Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-cogs" style="color: orange"></i>';
        } elseif ($count === 0) {
            return '<i class="fa fa-check-square-o" style="color: green"></i>';
        } else {
            return '<i class="fa fa-warning" style="color: red"></i>&nbsp;<a href="compatibility_issues.html#analyzer=' . $this->toId($analyzer) . '">' . $count . ' warnings</a>';
        }
    }

    protected function toId(string $name): string {
        return str_replace(array('/', '*', '(', ')', '.'), '_', strtolower($name));
    }

    protected function toOnlineId(string $name): string {
        return str_replace(array(' ', '(', ')', '/'), '-', strtolower($name));
    }

    protected function makeAuditDate(string &$finalHTML): void {
        $audit_date = 'Audit date : ' . date('d-m-Y h:i:s', time());
        $audit_name = $this->dump->fetchHash('audit_name')->toString();
        if (!empty($audit_name)) {
            $audit_date .= " - &quot;$audit_name&quot;";
        }

        $exakat_version = $this->dump->fetchHash('exakat_version')->toString();
        $exakat_build = $this->dump->fetchHash('exakat_build')->toString();
        $audit_date .= " - Exakat $exakat_version ($exakat_build)";
        $finalHTML = $this->injectBloc($finalHTML, 'AUDIT_DATE', $audit_date);
    }

    protected function getVCSInfo(): array {
        $info = array();

        $vcsClass = Vcs::getVCS($this->config);
        $vcsName = explode('\\', $vcsClass);
        $vcsName = array_pop($vcsName);
        switch($vcsName) {
            case 'Git':
                $info[] = array('Git URL', $this->dump->fetchHash('vcs_url')->toString());

                $res = $this->dump->fetchHash('vcs_branch')->toString();
                if (!empty($res)) {
                    $info[] = array('Git branch', trim($res));
                }

                $res = $this->dump->fetchHash('vcs_revision')->toString();
                if (!empty($res)) {
                    $info[] = array('Git commit', trim($res));
                }
                break 1;

            case 'Svn':
                $info[] = array('SVN URL', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Bazaar':
                $info[] = array('Bazaar URL', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Composer':
                $info[] = array('Package', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Mercurial':
                $info[] = array('Hg URL', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Copy':
                $info[] = array('Original path', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Symlink':
                $info[] = array('Original path', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Tarbz':
                $info[] = array('Source URL', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            case 'Targz':
                $info[] = array('Source URL', $this->dump->fetchHash('vcs_url')->toString());
                break 1;

            default :
                $info[] = array('Repository URL', 'Downloaded archive');
        }

        return $info;
    }

    protected function makeDocLink(string $analyzer): string {
        $docs = $this->docs->getDocs($analyzer, 'name');
        assert(!is_array($docs), "Missing docs('name') for $analyzer");
        return "<a href=\"analyses_doc.html#{$this->toId($analyzer)}\" id=\"{$this->toId($analyzer)}\"><i class=\"fa fa-book\" style=\"font-size: 14px\"></i></a> &nbsp; $docs";
    }

    protected function toHtmlList(array $array): string {
        return '<ul><li>' . implode("</li>\n<li>", $array) . '</li></ul>';
    }

    protected function getTotalAnalyzer(): array {
        return $this->dump->getTotalAnalyzer();
    }

    protected function generateAppinfo(Section $section): void {
        $data = new Data\Appinfo($this->dump);
        $data->prepare();

        $list = array();
        $originals = $data->originals();
        foreach($data->values() as $group => $points) {
            $listPoint = array();
            foreach($points as $point => $status) {

                if (isset($originals[$group][$point], $this->frequences[$originals[$group][$point]])) {
                    $percentage = $this->frequences[$originals[$group][$point]];
                    $percentageDisplay = "$percentage %";
                } else {
                    $percentage = 0;
                    $percentageDisplay = '&nbsp;';
                }

                $statusIcon = $this->makeIcon($status);
                $htmlPoint = makeHtml($point);
                $listPoint[] = <<<HTML
<li><div style="width: 90%; text-align: left;display: inline-block;">$statusIcon&nbsp;$htmlPoint&nbsp;</div><div style="display: inline-block; width: 10%;"><span class="progress progress-sm"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: $percentage%; color:black;">$percentageDisplay</div><div>&nbsp;</div></span></li>
HTML;
            }

            $listPoint = implode(PHP_EOL, $listPoint);
            $list[] = <<<HTML
        <ul class="sidebar-menu">
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>$group</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                $listPoint
            </ul>
          </li>
        </ul>
HTML;
        }

        $list = implode("\n", $list);
        $list = <<<HTML
        <div class="sidebar">
$list
        </div>
HTML;

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'APPINFO', $list);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateInventoriesEncoding(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('Mbstring Encodings');
        if ($res->isEmpty()) {
            $this->emptyResult($section);

            return ;
        }

        $values = $res->toHash('key', 'value');
        asort($values);

        $theTable = array();
        foreach($values as $encoding => $count) {
            $codeHtml = PHPSyntax($encoding);
            $theTable []= "<tr><td>{$codeHtml}</td><td>$count</td><td>&nbsp</td></tr>";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Names of the encoding used in the code');
        $html = $this->injectBloc($html, 'TABLE', implode(PHP_EOL, $theTable));
        $this->putBasedPage($section->file, $html);
    }

    protected function generateInventoriesOpenSSLCiphers(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('OpenSSL Ciphers');

        $values = $res->toHash('key', 'value');
        asort($values);

        $theTable = array();
        foreach($values as $encoding => $count) {
            $codeHtml = PHPSyntax($encoding);
            $theTable []= "<tr><td>{$codeHtml}</td><td>$count</td><td>&nbsp</td></tr>";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'List of all the OpenSSL cipher used in the code.');
        $html = $this->injectBloc($html, 'TABLE', implode(PHP_EOL, $theTable));
        $this->putBasedPage($section->file, $html);
    }

    protected function generateInventoriesProtocols(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('Protocols');

        $values = $res->toHash('key', 'value');
        asort($values);

        $theTable = array();
        foreach($values as $encoding => $count) {
            $codeHtml = PHPSyntax($encoding);
            $theTable []= "<tr><td>{$codeHtml}</td><td>$count</td><td>&nbsp</td></tr>";
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'List of all PHP protocols used in the code.');
        $html = $this->injectBloc($html, 'TABLE', implode(PHP_EOL, $theTable));
        $this->putBasedPage($section->file, $html);
    }

    protected function generateFixesRector(Section $section): void {
        $rector = new Rector();
        $report = $rector->generate('', self::INLINE);

        $configline = trim($report);
        $configline = str_replace(array(' ', "\n") , array('&nbsp;', "<br />\n", ), $configline);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPILATION', $configline);
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $html);
    }

    protected function generateFixesPhpCsFixer(Section $section): void {
        $phpcsfixer = new Phpcsfixer();
        $report = $phpcsfixer->generate('', self::INLINE);

        $configline = trim($report);
        $configline = PHPSyntax($configline);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'COMPILATION', $configline);
        $html = $this->injectBloc($html, 'TITLE', $section->title);

        $this->putBasedPage($section->file, $html);
    }

    protected function generateIndentationLevelsBreakdown(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('Dump/IndentationLevels');
        if ($res->isEmpty()) {
            $this->emptyResult($section);

            return ;
        }

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['key'] . ' level '] = (int) $value['value'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . ' levels</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Indentation levels', $data, $html);
    }

    private function generateTypehintSuggestions(Section $section): void {
//        $constants  = $this->generateVisibilityConstantSuggestions();
//        $properties = $this->generateVisibilityPropertySuggestions();
        $methods    = $this->generateTypehintMethodsSuggestions();

        $classes = array_unique(array_merge(array_keys($methods)));

        $headers = <<<'HTML'
    <tr>
        <td>&nbsp;</td>
        <td>Method</td>
        <td>Argument</td>
        <td>Typehint</td>
        <td>Default</td>
    </tr>
HTML;

        $visibilityTable = array('<table class="table table-striped">',
                                 $headers,
                                 );

        foreach($classes as $id) {
            list(, $class) = explode(':', $id);
            $visibilityTable []= '<tr><td colspan="9">' . PHPsyntax($class) . '</td></tr>' . PHP_EOL .
                                $headers . PHP_EOL .
//                                (isset($constants[$id])  ? implode('', $constants[$id])  : '') .
//                                (isset($properties[$id]) ? implode('', $properties[$id]) : '') .
                                (isset($methods[$id]) ? implode('', $methods[$id]) : '');
        }

        $visibilityTable []= '</table>';
        $visibilityTable = implode(PHP_EOL, $visibilityTable);

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $html = $this->injectBloc($html, 'DESCRIPTION', 'Below, is a summary of all classes and their parameters\'s typehinting status. ');
        $html = $this->injectBloc($html, 'CONTENT', $visibilityTable);
        $this->putBasedPage($section->file, $html);
    }

    protected function generateDereferencingLevelsBreakdown(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('Dump/DereferencingLevels');
        if ($res->isEmpty()) {
            $this->emptyResult($section);
            return ;
        }

        $html = array();
        $data = array();
        foreach ($res->toArray() as $value) {
            $data["'{$value['key']} level'"] = (int) $value['value'];

            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $value['key'] . ' levels</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Dereferencing levels', $data, $html);
    }

    private function generateTypehintMethodsSuggestions(): array {
        $res = $this->dump->fetchTableMethodsByArgument();
        $arguments = array();
        foreach($res->toArray() as $row) {
            $theMethod = $row['fullnspath'];
            $visibilities = array($row['typehint'], $row['init']);

            $argument = '<tr><td>&nbsp;</td><td>&nbsp;</td><td>' . PHPSyntax($row['argument']) . '</td><td class="exakat_short_text">' .
                                    implode('</td><td>', $visibilities)
                                 . '</td></tr>' . PHP_EOL;

            array_collect_by($arguments, $theMethod, $argument);
        }

        $return = array();
        $res = $this->dump->fetchTableMethodsByReturntype();
        foreach($res->toArray() as $row) {
            $visibilities = array($row['returntype'], '&nbsp;');

            $method = '<tr><td>&nbsp;</td><td>' . PHPSyntax($row['method']) . '</td><td>&nbsp;</td><td class="exakat_short_text">' .
                                    implode('</td><td>', $visibilities)
                                 . '</td></tr>' . PHP_EOL;
            $method .= implode(PHP_EOL, $arguments[$row['fullnspath'] . '::' . mb_strtolower($row['method'])] ?? array());

            array_collect_by($return, $row['fullnspath'] . ':' . $row['theClass'], $method);
        }

        unset($return['']);

        return $return;
    }

    protected function generateForeachFavorites(Section $section): void {
        // List of indentation used
        $res = $this->dump->fetchHashResults('Foreach Names');
        $res->map(function (array $x): array { $x['key'] = str_replace('&', '', $x['key']); return $x; });

        // merging results from &$v and $v into one
        $data = array();
        foreach ($res->toArray() as $value) {
            $data[$value['key']] =
                (int) $value['value'] + ($data[$value['key']] ?? 0);
        }
        arsort($data);

        $html = array();
        foreach ($data as $key => $value) {
            $html []= '<div class="clearfix">
                      <div class="block-cell-name">' . $key . '</div>
                      <div class="block-cell-issue text-center">' . $value . '</div>
                  </div>';
        }
        $html = implode(PHP_EOL, $html);

        $this->generateGraphList($section->file, $section->title, 'Foreach names', $data, $html);
    }

    protected function generateUsedMagic(Section $section): void {
        $results = $this->dump->fetchAnalysers(array('Classes/MagicMethod',
                                                     'Classes/MagicProperties',
                                                     ));
        $results->load();

        $expr = $results->getColumn('fullcode');
        $expr = array_map(function (string $x): string { return trim($x, '{}');}, $expr);
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