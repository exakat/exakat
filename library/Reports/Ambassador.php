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

namespace Reports;

class Ambassador extends Reports {

    protected $dump = null; // Dump.sqlite
    protected $analyzers = array(); // cache for analyzers [Title] = object
    protected $projectPath = null;
    protected $finalName = null;
    private $tmpName = '';

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get the base file
     *
     * @param type $file
     * @return type
     */
    private function getBasedPage($file) {
        $baseHTML = file_get_contents($this->tmpName . '/datas/base.html');
        $title = ($file == 'index') ? 'Dashboard' : $file;
        $baseHTML = $this->injectBloc($baseHTML, "TITLE", $title);
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
     * @return type
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

        $this->generateDocumentation();
        $this->generateDashboard();
        $this->generateFiles();
        $this->generateAnalyzers();
        $this->generateIssues();

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
     * @return type
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
            if(count($fileLines) > $lineNumber){
                $startLine = $lineNumber-$numberBeforeAndAfter;
                if($startLine<0)
                    $startLine=0;

                if($lineNumber+$numberBeforeAndAfter < count($fileLines)-1 )
                {
                    $endLine = $lineNumber+$numberBeforeAndAfter;
                }else{
                    $endLine = (count($fileLines)-1);
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

        foreach(\Analyzer\Analyzer::getThemeAnalyzers($this->config->thema) as $analyzer) {
            $analyzer = \Analyzer\Analyzer::getInstance($analyzer);
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
        $finalHTML = str_replace("SCRIPTDATAANALYZERLIST", $analyzerOverview['scriptDataAnalyzer'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERMAJOR", $analyzerOverview['scriptDataAnalyzerMajor'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERCRITICAL", $analyzerOverview['scriptDataAnalyzerCritical'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERNONE", $analyzerOverview['scriptDataAnalyzerNone'], $finalHTML);
        $finalHTML = str_replace("SCRIPTDATAANALYZERMINOR", $analyzerOverview['scriptDataAnalyzerMinor'], $finalHTML);

        file_put_contents($this->tmpName . '/datas/index.html', $finalHTML);
    }

    /**
     * Get info bloc top left
     *
     * @return string
     */
    public function getHashData() {
        $php = new \Phpexec($this->config->phpversion);

        $datastore = new \Datastore($this->config);
        $info = array(
            'Number of PHP files' => $datastore->getHash('files'),
            'Number of lines of code' => $datastore->getHash('loc'),
            'Number of lines of code with comments' => $datastore->getHash('locTotal'),
            'PHP used' => $php->getActualVersion() //.' (version '.$this->config->phpversion.' configured)'
        );

        // fichier
        $totalFileAnalysed = $this->getTotalAnalysedFile(true);
        $totalFile = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFile['totalanalysedfile'] - $totalFileAnalysed['totalanalysedfile'];
        $porcentFile = ($totalFileSansError / $totalFile['totalanalysedfile']) * 100;
        // analyzer
        $totalAnalyzerUsed = $this->getTotalAnalyzer(true);
        $totalAnalyzer = $this->getTotalAnalyzer();
        $totaalAnalyzerSansError = $totalAnalyzer['totalanalyzer'] - $totalAnalyzerUsed['totalanalyzer'];
        $pourcentAnalyzer = ($totaalAnalyzerSansError / $totalAnalyzer['totalanalyzer']) * 100;

        $html = '<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Project Overview</h3>
                    </div>

                    <div class="box-body chart-responsive">
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of Php</span> files</p>
                                <p class="value">' . $info['Number of PHP files'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">' . $info['PHP used'] . '</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span># of</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code with comments'] . '</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Filename free of issues</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . round($porcentFile) . '%">
                                        <span class="sr-only">20% Complete</span>
                                    </div>
                                </div>
                                <div class="pourcentage">' . round($porcentFile) . '%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Annalyser free of issues</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . round($pourcentAnalyzer) . '%">
                                        <span class="sr-only">20% Complete</span>
                                    </div>
                                </div>
                                <div class="pourcentage">' . round($pourcentAnalyzer) . '%</div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    /**
     * Get Issues Breakdown
     *
     * @return type
     */
    public function getIssuesBreakdown() {
        $receipt = array('Code Smells'  => 'Analyze',
                         'Dead Code'    => 'Dead code',
                         'Security'     => 'Security',
                         'Performances' => 'Performances');

        $data = array();
        foreach ($receipt AS $key => $categorie) {
            $data[] = ['label' => $key, 'value' => count(\Analyzer\Analyzer::getThemeAnalyzers($categorie))];
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

        return array('html' => $issuesHtml, 'script' => $dataScript);
    }

    /**
     * Severity Breakdown
     *
     * @return type
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

        return array('html' => $html, 'script' => $dataScript);
    }

    /**
     * Liste fichier analysé
     *
     * @return type
     */
    private function getTotalAnalysedFile($issues = false) {
        // sous requete
        $sQuery = "SELECT r.*, rc.* from results as r
                    JOIN resultsCounts as rc on  rc.analyzer = r.analyzer";
        if ($issues) {
            $sQuery .= " WHERE rc.count > 0";
        }
        $sQuery .= " GROUP BY file";

        $query = "SELECT count(*) AS totalanalysedfile "
                . "FROM (" . $sQuery . ")";
        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray();
    }

    /**
     * Liste analyzer
     *
     * @param type $issues
     * @return type
     */
    private function getTotalAnalyzer($issues = false) {
        $query = "SELECT count(*) AS totalanalyzer FROM resultsCounts ";
        if ($issues) {
            $query .= " WHERE count > 0";
        }
        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray();
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
            $analyserHTML.='<td><a href="#" title="' . $analyser["Label"] . '">' . $analyser["Label"] . '</a></td>
                        <td>' . $analyser["Type"] . '</td>
                        <td>' . $analyser["Receipt"] . '</td>
                        <td>' . $analyser["Issues"] . '</td>
                        <td>' . $analyser["Files"] . '</td>
                        <td>' . $analyser["Severity"] . '</td>';
            $analyserHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-ANALYZERS", $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        file_put_contents($this->tmpName . '/datas/analyzers.html', $finalHTML);
    }

    /**
     * Get list of analyzers
     *
     * @return string
     */
    protected function getAnalyzersResultsCounts() {
        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS Issues, severity AS Severity FROM results
        WHERE analyzer IN $this->themesList
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
            $row['Files'] = $this->getCountFileByAnalyzers($row['analyzer']);
            $row['Label'] = $analyzer->getDescription()->getName();
            $row['Receipt'] = 'B'; //implode(', ', $analyzer->getThemeAnalyzers($this->config->thema));
            $row['Type'] = 'null';

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Nombre fichier qui ont l'analyzer
     *
     * @param type $analyzer
     * @return type
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
            $filesHTML.='<td><a href="#" title="' . $file["Filename"] . '">' . $file["Filename"] . '</a></td>
                        <td>' . $file["LoC"] . '</td>
                        <td>' . $file["Issues"] . '</td>
                        <td>' . $file["Analysers"] . '</td>
                        <td>' . $file["Duplication"] . '</td>';
            $filesHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, "BLOC-FILES", $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, "BLOC-JS", '<script src="scripts/datatables.js"></script>');

        file_put_contents($this->tmpName . '/datas/files.html', $finalHTML);
    }

    /**
     * Get list of file
     *
     * @return type
     */
    private function getFilesResultsCounts() {
        $result = $this->sqlite->query(<<<SQL
SELECT file AS Filename, line AS LoC, count(*) AS Issues FROM results
        WHERE analyzer IN $this->themesList
        GROUP BY file
SQL
        );
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $row['Analysers'] = $this->getCountAnalyzersByFile($row['Filename']);
            $row['Duplication'] = $this->getDuplicationFileByAnalyzer($row['Filename']);

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Nombre analyzer par fichier
     *
     * @param type $file
     * @return type
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
     * Nombre duplication analyzer par fichier
     *
     * @param type $file
     * @return type
     */
    private function getDuplicationFileByAnalyzer($file) {
        $query = <<<'SQL'
                SELECT analyzer, count(*)  AS number FROM results WHERE file = :file
                    GROUP BY analyzer
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
     * @return type
     */
    public function getFilesCount($limit) {
        $query = "SELECT file, count(*) AS number
                    FROM results
                    GROUP BY file
                    ORDER BY number DESC ";
        if ($limit) {
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
     * @return type
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

        return $html;
    }

    /**
     * Get data files overview
     * 
     * @return type
     */
    private function getFileOverview() {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis = '';
        $dataMajor = '';
        $dataCritical = '';
        $dataNone = '';
        $dataMinor = '';
        foreach ($data as $value) {
            $xAxis .= ($xAxis) ? ', ' . "'" . $value['file'] . "'" : "'" . $value['file'] . "'";
            $severity = $this->getSeverityNumberByFile($value['file']);
            foreach ($severity as $severityValue) {
                if ($severityValue['severity'] == 'Major') {
                    $dataMajor .= ($dataMajor != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'Critical') {
                    $dataCritical .= ($dataCritical != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'None') {
                    $dataNone .= ($dataNone != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'Minor') {
                    $dataMinor .= ($dataMinor != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
            }
        }

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
     * @return type
     */
    private function getAnalyzersCount($limit) {
        $query = "SELECT analyzer, count(*) AS number
                    FROM results
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
     * @return type
     */
    private function getTopAnalyzers() {
        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    GROUP BY analyzer
                    ORDER BY number DESC
                    LIMIT " . self::TOPLIMIT;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray()) {
            $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
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
     * @return type
     */
    private function getSeverityNumberByFile($file) {
        $query = <<<'SQL'
                SELECT severity, count(*) AS number
                    FROM results
                    WHERE file = :file
                    GROUP BY severity
                    ORDER BY number DESC
SQL;

        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':file', $file, SQLITE3_TEXT);
        $result = $stmt->execute();

        $data = array();
        $severityType = ['Major', 'Critical', 'None', 'Minor'];
        $severityExiste = array();
        $count = 0;
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $count ++;
            $severityExiste[] = $row['severity'];
            $data[] = array('severity' => $row['severity'], 'value' => $row['number']);
        }
        if (count($severityType) > $count) {
            $datasup = array_diff($severityType, $severityExiste);
            foreach ($datasup as $sup) {
                $data[] = array('severity' => $sup, 'value' => 0);
            }
        }

        return $data;
    }
    
    /**
     * Get data analyzer overview
     * 
     * @return type
     */
    private function getAnalyzerOverview() {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis = '';
        $dataMajor = '';
        $dataCritical = '';
        $dataNone = '';
        $dataMinor = '';

        foreach ($data as $value) {
            $xAxis .= ($xAxis) ? ', ' . "'" . $value['analyzer'] . "'" : "'" . $value['analyzer'] . "'";
            $severity = $this->getSeverityNumberByAnalyzer($value['analyzer']);
            foreach ($severity as $severityValue) {
                if ($severityValue['severity'] == 'Major') {
                    $dataMajor .= ($dataMajor != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'Critical') {
                    $dataCritical .= ($dataCritical != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'None') {
                    $dataNone .= ($dataNone != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
                if ($severityValue['severity'] == 'Minor') {
                    $dataMinor .= ($dataMinor != '') ? ', ' . $severityValue['value'] : $severityValue['value'];
                }
            }
        }

        return array(
            'scriptDataAnalyzer' => $xAxis,
            'scriptDataAnalyzerMajor' => $dataMajor,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerNone' => $dataNone,
            'scriptDataAnalyzerMinor' => $dataMinor
        );
    }
    
    /**
     * Nombre severity by analyzer en Dashboard
     *
     * @return type
     */
    private function getSeverityNumberByAnalyzer($analyzer) {
        $query = <<<'SQL'
                SELECT severity, count(*) AS number
                    FROM results
                    WHERE analyzer = :analyzer
                    GROUP BY severity
                    ORDER BY number DESC
SQL;

        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, SQLITE3_TEXT);
        $result = $stmt->execute();

        $data = array();
        $severityType = ['Major', 'Critical', 'None', 'Minor'];
        $severityExiste = array();
        $count = 0;
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $count ++;
            $severityExiste[] = $row['severity'];
            $data[] = array('severity' => $row['severity'], 'value' => $row['number']);
        }
        if (count($severityType) > $count) {
            $datasup = array_diff($severityType, $severityExiste);
            foreach ($datasup as $sup) {
                $data[] = array('severity' => $sup, 'value' => 0);
            }
        }

        return $data;
    }
    
    /**
     * generate the content of Issues
     */
    private function generateIssues()
    {
        $baseHTML = file_get_contents($this->tmpName . '/datas/issues.html');
        $issues = $this->getIssuesFaceted();
        $finalHTML = str_replace("SCRIPT_DATA_FACETED", implode(",", $issues), $baseHTML);

        file_put_contents($this->tmpName . '/datas/issues.html', $finalHTML);
    }

    /**
     * List of Issues faceted
     * @return array
     */
    public function getIssuesFaceted()
    {
        $sqlQuery = <<<SQL
            SELECT fullcode, file, line, analyzer
                FROM results
                WHERE analyzer IN $this->themesList

SQL;
        $result = $this->sqlite->query($sqlQuery);

        $items = array();
        while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $item = array();
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
            $item['analyzer'] =  $ini['name'];
            $item['analyzer_md5'] = md5($ini['name']);
            $item['file' ] =  $row['file'];
            $item['file_md5' ] =  md5($row['file']);
            $item['code' ] = $row['fullcode'];
            $item['code_detail'] = "<i class=\"fa fa-plus \"></i>";
            $item['code_plus'] = "\$this->setRunner(\$runner);}\rpublic function() {\r}";
            $item['link_file'] = "#";
            $item['line' ] =  $row['line'];
            $item['severity'] = "<i class=\"fa fa-warning " . $this->getClassByType($analyzer->getSeverity()) . "\"></i>";
            $item['complexity'] = "<i class=\"fa fa-cog " . $this->getClassByType($analyzer->getTimeToFix()) . "\"></i>";
            $item['receipt' ] =  'A';//$analyzer->getReceipt($this->config->thema);
            $item['analyzer_help' ] =  $ini['description'];

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

        $datastore = new \Datastore($this->config);
        
        $info[] = array('Number of PHP files', $datastore->getHash('files'));
        $info[] = array('Number of lines of code', $datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));
        
        $php = new \Phpexec($this->config->phpversion);
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));
        
        $info[] = array('Exakat version', \Exakat::VERSION. ' ( Build '. \Exakat::BUILD . ') ');
        
        $settings = '';
        foreach($info as $i) {
            $settings .= "<tr><td>$i[0]</td><td>$i[1]</td></tr>";
        }        
        
        $file = $this->tmpName.'/datas/used_settings.html';
        $html = file_get_contents($file);
        $html = str_replace('<settings />', $settings, $html);
        file_put_contents($file, $html);
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

        $file = $this->tmpName.'/datas/proc_files.html';
        $html = file_get_contents($file);
        $html = str_replace('<files />', $files, $html);
        $html = str_replace('<non-files />', $nonFiles, $html);
        file_put_contents($file, $html);
    }

}

?>