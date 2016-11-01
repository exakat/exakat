<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Analyzer\Docs;
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Ambassador extends Reports {

    protected $dump            = null; // Dump.sqlite
    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    private $tmpName           = '';
    
    private $docs              = null;
    private $timesToFix        = null;
    private $themesForAnalyzer = null;
    private $severities        = null;

    private $themesToShow = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 
                                  'CompatibilityPHP70', 'CompatibilityPHP71',
                                  '"Dead code"', 'Security', 'Analyze');

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->docs              = new Docs($this->config->dir_root.'/data/analyzers.sqlite');
        $this->timesToFix        = $this->docs->getTimesToFix();
        $this->themesForAnalyzer = $this->docs->getThemesForAnalyzer();
        $this->severities        = $this->docs->getSeverities();
    }

    /**
     * Get the base file
     *
     * @param type $file
     */
    private function getBasedPage($file) {
        $baseHTML = file_get_contents($this->tmpName . '/datas/base.html');
        $title = ($file == 'index') ? 'Dashboard' : $file;
        $baseHTML = $this->injectBloc($baseHTML, "TITLE", $title);
        $baseHTML = $this->injectBloc($baseHTML, "PROJECT", $this->config->project);
        $baseHTML = $this->injectBloc($baseHTML, "PROJECT_LETTER", strtoupper($this->config->project{0}));

        $subPageHTML = file_get_contents($this->tmpName . '/datas/' . $file . '.html');

        $combinePageHTML = $this->injectBloc($baseHTML, "BLOC-MAIN", $subPageHTML);

        return $combinePageHTML;
    }

    /**
     * Inject bloc in html content
     *
     * @param type $html
     * @param type $bloc
     * @param type $content
     */
    private function injectBloc($html, $bloc, $content) {
        return str_replace("{{" . $bloc . "}}", $content, $html);
    }

    public function generateFileReport($report) {
        
    }

    /**
     * Generate the report
     *
     * @param type $folder
     * @param type $name
     */
    public function generate($folder, $name = 'report') {
        $this->finalName = $folder . '/' . $name;
        $this->tmpName = $folder . '/.' . $name;

        $this->projectPath = $folder;
        
        $this->initFolder();
        $this->generateSettings();
        $this->generateProcFiles();  
        $this->generateCodes();  

        $this->generateDocumentation();
        $this->generateDashboard();
        $this->generateFiles();
        $this->generateAnalyzers();

        $this->generateIssues();
        $this->generateAnalyzersList();
        $this->generateExternalLib();

        $files = ['base', 'index', 'credits'];
        foreach($files as $file) {
            $baseHTML = file_get_contents($this->tmpName . '/datas/'.$file.'.html');
            $baseHTML = $this->injectBloc($baseHTML, "PROJECT", $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, "PROJECT_LETTER", strtoupper($this->config->project{0}));
            file_put_contents($this->tmpName . '/datas/'.$file.'.html', $baseHTML);
        }
        
        $this->cleanFolder();
    }

    /**
     * Clear and init folder
     *
     * @return string
     */
    private function initFolder() {
        if ($this->finalName === null) {
            return "Can't produce Devoops format to stdout";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root . '/media/devfaceted', $this->tmpName );
    }

    /**
     * Clear existant folder
     *
     */
    private function cleanFolder() {
        if (file_exists($this->tmpName . '/base.html')) {
            unlink($this->tmpName . '/base.html');
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName . " folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    private function getLinesFromFile($filePath,$lineNumber,$numberBeforeAndAfter){
        $lineNumber--; // array index
        $lines = array();
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath)) {

            $fileLines = file($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath);

            $startLine = 0;
            $endLine = 10;
            if(count($fileLines) > $lineNumber) {
                $startLine = $lineNumber-$numberBeforeAndAfter;
                if($startLine<0)
                    $startLine=0;

                if($lineNumber+$numberBeforeAndAfter < count($fileLines)-1 ) {
                    $endLine = $lineNumber+$numberBeforeAndAfter;
                } else {
                    $endLine = count($fileLines)-1;
                }
            }

            for ($i=$startLine; $i < $endLine+1 ; $i++) {
                $lines[]= array(
                            "line"=>$i+1,
                            "code"=>$fileLines[$i]
                    );
            }
        }
        return $lines;
    }

    private function setPHPBlocs($description){
        $description = str_replace("<?php", '</p><pre><code class="php">&lt;?php', $description);
        $description = str_replace("?>", '?&gt;</code></pre><p>', $description);
        return $description;
    }

    private function generateDocumentation(){
        $datas = array();
        $baseHTML = $this->getBasedPage("analyzers_doc");
        $analyzersDocHTML = "";

        foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
            $analyzer = Analyzer::getInstance($analyzer);
            $description = $analyzer->getDescription();
            $analyzersDocHTML.='<h2><a href="issues.html?analyzer='.md5($description->getName()).'">'.$description->getName().'</a></h2>';
            $analyzersDocHTML.='<p>'.$this->setPHPBlocs($description->getDescription()).'</p>';

            if(!empty($description->getClearPHP())){
                $analyzersDocHTML.='<p>This rule is named <a target="_blank" href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$description->getClearPHP().'.md">'.$description->getClearPHP().'</a>, in the clearPHP reference.</p>';
            }
        }
        $finalHTML = $this->injectBloc($baseHTML, "BLOC-ANALYZERS", $analyzersDocHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/highlight.pack.js"></script>');

        file_put_contents($this->tmpName . '/datas/analyzers_doc.html', $finalHTML);
    }

    /**
     * generate the content of Dashboad
     */
    public function generateDashboard() {
        $baseHTML = file_get_contents($this->tmpName . '/datas/index.html');

        // Bloc top left
        $hashData = $this->getHashData();
        $finalHTML = $this->injectBloc($baseHTML, "BLOCHASHDATA", $hashData);

        // bloc Issues
        $issues = $this->getIssuesBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, "BLOCISSUES", $issues['html']);
        $finalHTML = str_replace("SCRIPTISSUES", $issues['script'], $finalHTML);

        // bloc severity
        $severity = $this->getSeverityBreakdown();
        $finalHTML = $this->injectBloc($finalHTML, "BLOCSEVERITY", $severity['html']);
        $finalHTML = str_replace("SCRIPTSEVERITY", $severity['script'], $finalHTML);

        // top 10
        $fileHTML = $this->getTopFile();
        $finalHTML = $this->injectBloc($finalHTML, "TOPFILE", $fileHTML);
        $analyzerHTML = $this->getTopAnalyzers();
        $finalHTML = $this->injectBloc($finalHTML, "TOPANALYZER", $analyzerHTML);

        // Filename Overview
        $fileOverview = $this->getFileOverview();
        $finalHTML = str_replace("SCRIPTDATAFILES", $fileOverview['scriptDataFiles'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAMAJOR", $fileOverview['scriptDataMajor'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATACRITICAL", $fileOverview['scriptDataCritical'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATANONE", $fileOverview['scriptDataNone'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAMINOR", $fileOverview['scriptDataMinor'], $finalHTML);
        
        // Analyzer Overview
        $analyzerOverview = $this->getAnalyzerOverview();
        $finalHTML = str_replace("SCRIPTDATAANALYZERLIST",     $analyzerOverview['scriptDataAnalyzer'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERMAJOR",    $analyzerOverview['scriptDataAnalyzerMajor'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERCRITICAL", $analyzerOverview['scriptDataAnalyzerCritical'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERNONE",     $analyzerOverview['scriptDataAnalyzerNone'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERMINOR",    $analyzerOverview['scriptDataAnalyzerMinor'], $finalHTML);

        file_put_contents($this->tmpName . '/datas/index.html', $finalHTML);
    }

    /**
     * Get info bloc top left
     *
     * @return string
     */
    public function getHashData() {
        $php = new Phpexec($this->config->phpversion);

        $datastore = new Datastore($this->config);
        $info = array(
            'Number of PHP files'                   => $datastore->getHash('files'),
            'Number of lines of code'               => $datastore->getHash('loc'),
            'Number of lines of code with comments' => $datastore->getHash('locTotal'),
            'PHP used' => $php->getActualVersion() //.' (version '.$this->config->phpversion.' configured)'
        );

        // fichier
        $totalFile = $datastore->getHash('files');
        $totalFileAnalysed = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFileAnalysed - $totalFile;
        $percentFile = round($totalFileSansError / $totalFile) * 100;

        // analyzer
        list($totalAnalyzerUsed, $totalAnalyzerReporting) = $this->getTotalAnalyzer();
        $totaalAnalyzerWithoutError = $totalAnalyzerUsed - $totalAnalyzerReporting;
        $percentAnalyzer = round($totaalAnalyzerWithoutError / $totalAnalyzerUsed) * 100;

        $html = '<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Project Overview</h3>
                    </div>

                    <div class="box-body chart-responsive">
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of PHP</span> files</p>
                                <p class="value">' . $info['Number of PHP files'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">' . $info['PHP used'] . '</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>Total</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code with comments'] . '</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Files free of issues (%)</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentFile . '%">
                                        <span class="sr-only">20% Complete</span>
                                    </div>
                                </div>
                                <div class="pourcentage">' . $percentFile . '%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Analyzers free of issues (%)</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentAnalyzer . '%">
                                        <span class="sr-only">20% Complete</span>
                                    </div>
                                </div>
                                <div class="pourcentage">' . $percentAnalyzer . '%</div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    /**
     * Get Issues Breakdown
     *
     */
    public function getIssuesBreakdown() {
        $receipt = array('Code Smells'  => 'Analyze',
                         'Dead Code'    => 'Dead code',
                         'Security'     => 'Security',
                         'Performances' => 'Performances');

        $data = array();
        foreach ($receipt AS $key => $categorie) {
            $data[] = ['label' => $key, 'value' => count(Analyzer::getThemeAnalyzers($categorie))];
        }
        // ordonné DESC par valeur
        uasort($data, function ($a, $b) {
            if ($a['value'] > $b['value']) {
                return -1;
            } elseif ($a['value'] < $b['value']) {
                return 1;
            } else {
                return 0;
            }
        });
        $issuesHtml = '';
        $dataScript = '';

        foreach ($data as $key => $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= ($dataScript) ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html' => $issuesHtml, 'script' => $dataScript);
    }

    /**
     * Severity Breakdown
     *
     */
    public function getSeverityBreakdown() {
        $query = <<<SQL
                SELECT severity, count(*) AS number
                    FROM results
                    GROUP BY severity
                    ORDER BY number DESC
SQL;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $data[] = array('label' => $row['severity'], 'value' => $row['number']);
        }
        
        $html = '';
        $dataScript = '';
        foreach ($data as $key => $value) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= ($dataScript) ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">&nbsp;</div>
                   <div class="block-cell text-center">&nbsp;</div>
                 </div>';
        }

        return array('html' => $html, 'script' => $dataScript);
    }

    /**
     * Liste fichier analysé
     *
     */
    private function getTotalAnalysedFile() {
        $query = "SELECT COUNT(DISTINCT file) FROM results";
        $result = $this->sqlite->query($query);

        return $result->fetchArray(\SQLITE3_NUM)[0];
    }

    /**
     * Liste analyzer
     *
     * @param type $issues
     */
    private function getTotalAnalyzer($issues = false) {
        $query = "SELECT count(*) AS total, COUNT(CASE WHEN rc.count != 0 THEN 1 ELSE null END) AS yielding 
            FROM resultsCounts AS rc
            WHERE rc.count >= 0";

        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray(\SQLITE3_NUM);
    }

    /**
     * generate the content of liste analyzers
     */
    private function generateAnalyzers() {
        $analysers = $this->getAnalyzersResultsCounts();

        $baseHTML = $this->getBasedPage("analyzers");
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML.= "<tr>";
            $analyserHTML.='<td>' . $analyser["label"] . '</td>
                        <td>' . $analyser["recipes"] . '</td>
                        <td>' . $analyser["issues"] . '</td>
                        <td>' . $analyser["files"] . '</td>
                        <td>' . $analyser["severity"] . '</td>';
            $analyserHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-ANALYZERS", $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        print file_put_contents($this->tmpName . '/datas/analyzers.html', $finalHTML)." in analyzers\n";
    }

    /**
     * Get list of analyzers
     *
     * @return string
     */
    protected function getAnalyzersResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS issues, count(distinct file) AS files, severity AS severity FROM results
        WHERE analyzer IN ($list)
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );

        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = Analyzer::getInstance($row['analyzer']);
            $row['label'] = $analyzer->getDescription()->getName();
            $row['recipes' ] =  join(', ', $this->themesForAnalyzer[$row['analyzer']]);

            $return[] = $row;
        }

        return $return;
    }

    /**
     * Nombre fichier qui ont l'analyzer
     *
     * @param type $analyzer
     */
    private function getCountFileByAnalyzers($analyzer) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT file FROM results WHERE analyzer = :analyzer)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }

    /**
     * generate the content of liste files
     */
    private function generateFiles() {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage("files");
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= "<tr>";
            $filesHTML.='<td>' . $file["file"] . '</td>
                        <td>' . $file["loc"] . '</td>
                        <td>' . $file["issues"] . '</td>
                        <td>' . $file["analyzers"] . '</td>';
            $filesHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-FILES", $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        file_put_contents($this->tmpName . '/datas/files.html', $finalHTML);
    }

    /**
     * Get list of file
     *
     */
    private function getFilesResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $result = $this->sqlite->query(<<<SQL
SELECT file AS file, line AS loc, count(*) AS issues, count(distinct analyzer) AS analyzers FROM results
        GROUP BY file
SQL
        );
        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['file']] = $row;
        }

        return $return;
    }

    /**
     * Nombre analyzer par fichier
     *
     * @param type $file
     */
    private function getCountAnalyzersByFile($file) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT analyzer FROM results WHERE file = :file)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':file', $file, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }

    /**
     * List file with count
     *
     * @param type $limit
     */
    public function getFilesCount($limit = null) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT file, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY file
                    ORDER BY number DESC ";
        if ($limit !== null) {
            $query .= " LIMIT " . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $data[] = array('file' => $row['file'], 'value' => $row['number']);
        }

        return $data;
    }

    /**
     * Liste de top file
     *
     */
    private function getTopFile() {
        $data = $this->getFilesCount(self::TOPLIMIT);

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="' . $value['file'] . '">
                      <div class="block-cell-name">' . $value['file'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                    </a>
                  </div>';
        }
        $nb = 10 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                      <div class="block-cell-name">&nbsp;</div>
                      <div class="block-cell-issue text-center">&nbsp;</div>
                  </div>';
        }

        return $html;
    }

    /**
     * Get data files overview
     * 
     */
    private function getFileOverview() {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis        = [];
        $dataMajor    = [];
        $dataCritical = [];
        $dataNone     = [];
        $dataMinor    = [];
        $severities = $this->getSeveritiesNumberBy('file');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['file'] . "'";
            $dataCritical[] = empty($severities[$value['file']]['Critical']) ? 0 : $severities[$value['file']]['Critical'];
            $dataMajor[]    = empty($severities[$value['file']]['Major'])    ? 0 : $severities[$value['file']]['Major'];
            $dataMinor[]    = empty($severities[$value['file']]['Minor'])    ? 0 : $severities[$value['file']]['Minor'];
            $dataNone[]     = empty($severities[$value['file']]['None'])     ? 0 : $severities[$value['file']]['None'];
        }
        $xAxis        = join(', ', $xAxis);
        $dataCritical = join(', ', $dataCritical);
        $dataMajor    = join(', ', $dataMajor);
        $dataMinor    = join(', ', $dataMinor);
        $dataNone     = join(', ', $dataNone);

        return array(
            'scriptDataFiles' => $xAxis,
            'scriptDataMajor' => $dataMajor,
            'scriptDataCritical' => $dataCritical,
            'scriptDataNone' => $dataNone,
            'scriptDataMinor' => $dataMinor
        );
    }

    /**
     * List analyzer with count
     *
     * @param type $limit
     */
    private function getAnalyzersCount($limit) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC ";
        if ($limit) {
            $query .= " LIMIT " . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('analyzer' => $row['analyzer'], 'value' => $row['number']);
        }

        return $data;
    }

    /**
     * Liste de top analyzers
     *
     */
    private function getTopAnalyzers() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC
                    LIMIT " . self::TOPLIMIT;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $analyzer = Analyzer::getInstance($row['analyzer']);
            $data[] = array('label' => $analyzer->getDescription()->getName(), 'value' => $row['number']);
        }

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="' . $value['label'] . '">
                      <div class="block-cell-name">' . $value['label'] . '</div>
                      <div class="block-cell-issue text-center">' . $value['value'] . '</div>
                    </a>
                  </div>';
        }

        return $html;
    }

    /**
     * Nombre severity by file en Dashboard
     *
     */
    private function getSeveritiesNumberBy($type = 'file') {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY $type, severity
SQL;

        $stmt = $this->sqlite->query($query);

        $return = array();
        while ($row = $stmt->fetchArray(\SQLITE3_ASSOC) ) {
            if ( !isset($return[$row[$type]]) ) {
                $return[$row[$type]] = [$row['severity'] => $row['count']];
            } else {
                $return[$row[$type]][$row['severity']] = $row['count'];
            }
        }

        return $return;
    }
    
    /**
     * Get data analyzer overview
     * 
     */
    private function getAnalyzerOverview() {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis        = [];
        $dataMajor    = [];
        $dataCritical = [];
        $dataNone     = [];
        $dataMinor    = [];

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['analyzer'] . "'";
            $dataCritical[] = empty($severities[$value['analyzer']]['Critical']) ? 0 : $severities[$value['analyzer']]['Critical'];
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major'])    ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor'])    ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None'])     ? 0 : $severities[$value['analyzer']]['None'];
        }
        $xAxis = join(', ', $xAxis);
        $dataCritical = join(', ', $dataCritical);
        $dataMajor = join(', ', $dataMajor);
        $dataMinor = join(', ', $dataMinor);
        $dataNone = join(', ', $dataNone);

        return array(
            'scriptDataAnalyzer'         => $xAxis,
            'scriptDataAnalyzerMajor'    => $dataMajor,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerNone'     => $dataNone,
            'scriptDataAnalyzerMinor'    => $dataMinor
        );
    }
    
    /**
     * generate the content of Issues
     */
    private function generateIssues()
    {
        $baseHTML = file_get_contents($this->tmpName . '/datas/issues.html');
        $issues = $this->getIssuesFaceted();
        $finalHTML = str_replace("SCRIPT_DATA_FACETED", implode(",", $issues), $baseHTML);
        $finalHTML = $this->injectBloc($finalHTML, "PROJECT", $this->config->project);
        $finalHTML = $this->injectBloc($finalHTML, "PROJECT_LETTER", strtoupper($this->config->project{0}));

        file_put_contents($this->tmpName . '/datas/issues.html', $finalHTML);
    }

    /**
     * List of Issues faceted
     * @return array
     */
    public function getIssuesFaceted() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.join('", "', $list).'"';

        $sqlQuery = <<<SQL
            SELECT fullcode, file, line, analyzer
                FROM results
                WHERE analyzer IN ($list)

SQL;
        $result = $this->sqlite->query($sqlQuery);

        $items = array();
        while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $item = array();
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $item['analyzer'] =  $ini['name'];
            $item['analyzer_md5'] = md5($ini['name']);
            $item['file' ] =  $row['file'];
            $item['file_md5' ] =  md5($row['file']);
            $item['code' ] = $row['fullcode'];
            $item['code_detail'] = "<i class=\"fa fa-plus \"></i>";
            $item['code_plus'] = htmlentities($row['fullcode'], ENT_COMPAT | ENT_HTML401 , 'UTF-8');
            $item['link_file'] = $row['file'];
            $item['line' ] =  $row['line'];
            $item['severity'] = "<i class=\"fa fa-warning " . $this->severities[$row['analyzer']] . "\"></i>";
            $item['complexity'] = "<i class=\"fa fa-cog " . $this->timesToFix[$row['analyzer']] . "\"></i>";
            $item['recipe' ] =  join(', ', $this->themesForAnalyzer[$row['analyzer']]);
            $item['analyzer_help' ] =  explode("\n", $ini['description'])[0];

            $items[] = json_encode($item);
            $this->count();
        }

        return $items;
    }
    
    /**
     * Get class by type
     * 
     * @param type $type
     * @return string
     */
    private function getClassByType($type)
    {
        if ($type == 'Critical' || $type == 'Long') {
            $class = 'text-orange';
        } elseif ($type == 'Major' || $type == 'Slow') {
            $class = 'text-red';
        } elseif ($type == 'Minor' || $type == 'Quick') {
            $class = 'text-yellow';
        }  elseif ($type == 'Note' || $type == 'Instant') {
            $class = 'text-blue';
        } else {
            $class = 'text-gray';
        }
        
        return $class;
    }
    
    private function generateSettings() {

       $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        if (!empty($this->config->project_url)) {
            $info[] = array('Home page', '<a href="'.$this->config->project_url.'">'.$this->config->project_url.'</a>');
        }
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $info[] = array('Git URL', $r[1]);
            
            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git branch');
            $info[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git rev-parse HEAD');
            $info[] = array('Git commit', trim($res));
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $datastore = new Datastore($this->config);
        
        $info[] = array('Number of PHP files', $datastore->getHash('files'));
        $info[] = array('Number of lines of code', $datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));
        
        $php = new Phpexec($this->config->phpversion);
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));
        
        $info[] = array('Exakat version', Exakat::VERSION. ' ( Build '. Exakat::BUILD . ') ');
        
        $settings = '';
        foreach($info as $i) {
            $settings .= "<tr><td>$i[0]</td><td>$i[1]</td></tr>";
        }        
        
        $this->updateFile('used_settings.html', ['<settings />'     => $settings]);
    }

    private function generateProcFiles() {
        $files = '';
        $res = $this->datastore->query('SELECT file FROM files');
        while($row = $res->fetchArray()) {
            $files .= "<tr><td>{$row['file']}</td></tr>\n";
        }

        $nonFiles = '';
        $res = $this->datastore->query('SELECT file, reason FROM ignoredFiles');
        while($row = $res->fetchArray()) {
            if (empty($row['file'])) { continue; }

            $nonFiles .= "<tr><td>{$row['file']}</td><td>{$row['reason']}</td></tr>\n";
        }

        $this->updateFile('proc_files.html', ['<files />'     => $files, 
                                              '<non-files />' => $nonFiles]);
    }

    private function generateAnalyzersList() {
        $analyzers = '';

       foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
           $analyzer = Analyzer::getInstance($analyzer);
           $description = $analyzer->getDescription();
    
           $analyzers .= "<tr><td>".$description->getName()."</td></tr>\n";
        }

        $this->updateFile('proc_analyzers.html', ['<analyzers />' => $analyzers]);
    }

    private function generateExternalLib() {
        $externallibraries = json_decode(file_get_contents($this->config->dir_root.'/data/externallibraries.json'));

        $libraries = '';
        $res = $this->datastore->query('SELECT library AS Library, file AS Folder FROM externallibraries ORDER BY library');

        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $url = $externallibraries->{strtolower($row['Library'])}->homepage;
            $name = $externallibraries->{strtolower($row['Library'])}->name;
            if (empty($url)) {
                $homepage = '';
            } else {
                $homepage = "<a href=\"".$url."\">".$row['Library']."</a>";
            }
            $libraries .= "<tr><td>$name</td><td>$row[Folder]</td><td>$homepage</td></tr>\n";
        }
        
        $this->updateFile('ext_lib.html', ['<libraries />' => $libraries]);
    }

    private function updateFile($file, $blocks) {
        $filePath = $this->tmpName.'/datas/'.$file;
        $html = file_get_contents($filePath);

        $html = str_replace("{{PROJECT}}", $this->config->project, $html);
        $html = str_replace("{{PROJECT_LETTER}}", strtoupper($this->config->project{0}), $html);
        
        $html = str_replace(array_keys($blocks), array_values($blocks), $html);

        file_put_contents($filePath, $html);
    }

    private function generateCodes() {
        mkdir($this->tmpName.'/datas/sources/', 0755);

        $files = '';
        $res = $this->datastore->query('SELECT file FROM files ORDER BY file');
        while($row = $res->fetchArray()) {
            $id = str_replace('/', '_', $row['file']);
            $files .= '<li><a href="#" id="'.$id.'" class="menuitem">'.htmlentities($row['file'], ENT_COMPAT | ENT_HTML401 , 'UTF-8')."</a></li>\n";
            
            $subdirs = explode('/', dirname($row['file']));
            $dir = $this->tmpName.'/datas/sources';
            foreach($subdirs as $subdir) {
                $dir .= '/'.$subdir;
                if (!file_exists($dir)) { 
                    mkdir($dir, 0755); 
                }
            }

            $source = show_source(dirname($this->tmpName).'/code/'.$row['file'], true);
            file_put_contents($this->tmpName.'/datas/sources/'.$row['file'], substr($source, 6, -8));
        }
        

        $this->updateFile('codes.html', ['<files />' => $files]);
    }
}

?>