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
use Exakat\Exakat;
use Exakat\Reports\Devoops;

class ZendFramework extends Devoops {
    public function __construct() {
        parent::__construct();

        $this->themes = Analyzer::getThemeAnalyzers('ZendFramework');
        $this->themesList = '("'.implode('", "', $this->themes).'")';    
    }
    
    public function generate($folder, $name = 'report') {
        $finalName = $name;
        $name = '.'.$name;
        
        if ($name === null) {
            return "Can't produce Devoops format to stdout";
        }

        // Clean final destination
        if ($folder.'/'.$finalName !== '/') {
            rmdirRecursive($folder.'/'.$finalName);
        }

        if (file_exists($folder.'/'.$finalName)) {
            display ($folder.'/'.$finalName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        // Clean temporary destination
        if (file_exists($folder.'/'.$name)) {
            rmdirRecursive($folder.'/'.$name);
        }

        mkdir($folder.'/'.$name, Devoops::FOLDER_PRIVILEGES);
        mkdir($folder.'/'.$name.'/ajax', Devoops::FOLDER_PRIVILEGES);

        copyDir($this->config->dir_root.'/media/devoops/css', $folder.'/'.$name.'/css');
        copyDir($this->config->dir_root.'/media/devoops/img', $folder.'/'.$name.'/img');
        copyDir($this->config->dir_root.'/media/devoops/js', $folder.'/'.$name.'/js');
        copyDir($this->config->dir_root.'/media/devoops/plugins', $folder.'/'.$name.'/plugins');
        
        display("Copied media files");
        $this->dump      = new \Sqlite3($folder.'/dump.sqlite', SQLITE3_OPEN_READONLY);
        
        $this->datastore = new \sqlite3($folder.'/datastore.sqlite', \SQLITE3_OPEN_READONLY);
        
        // Compatibility
        $compatibility = array('Compilation' => 'Compilation');
        foreach($this->config->other_php_versions as $code) {
            if ($code == 52) { continue; }

            $version = $code[0].'.'.substr($code, 1);
            $compatibility['Compatibility '.$version] = 'Compatibility';
        }

        // Analyze
        $analyze = array();
        //count > 0 AND 
        print 'SELECT * FROM resultsCounts WHERE analyzer in '.$this->themesList.' ORDER BY id';
        $res = $this->sqlite->query('SELECT * FROM resultsCounts WHERE analyzer in '.$this->themesList);
        while($row = $res->fetchArray()) {
            $analyzer = Analyzer::getInstance($row['analyzer']);
            
            $this->analyzers[$analyzer->getDescription()->getName()] = $analyzer;
            $analyze[$analyzer->getDescription()->getName()] = 'OneAnalyzer';
        }
        uksort($analyze, function ($a, $b) { 
            return -strnatcmp($a,$b) ;
        });
        $analyze = array_merge(array('Results Counts' => 'AnalyzersResultsCounts'), 
                             $analyze);

        // Files
        $files = array();
        $res = $this->sqlite->query('SELECT DISTINCT file FROM results ORDER BY file');
        while($row = $res->fetchArray()) {
            $files[$row['file']] = 'OneFile';
        }
        $files = array_merge(array('Files Counts' => 'FilesResultsCounts'), $files);
        
        $summary = array(
            'Report presentation' => array('Audit configuration'    => 'AuditConfiguration',
                                           'Processed Files'        => 'ProcessedFiles',
                                           'Non-processed Files'    => 'NonProcessedFiles',
            ),
            'Zend Framework' => $analyze,
        );
        
        $summaryHtml = $this->makeSummary($summary);
        
        $faviconHtml = '';
        if (file_exists($this->config->dir_root.'/projects/'.$this->config->project.'/code/favicon.ico')) {
            // Should be checked and reported
            copy($this->config->dir_root.'/projects/'.$this->config->project.'/code/favicon.ico', $folder.'/'.$name.'/img/'.$this->config->project.'.ico');
            
            $faviconHtml = <<<HTML
<img src="img/{$this->config->project}.ico" class="img-circle" alt="{$this->config->project} logo" />
HTML;

            if (!empty($this->config->project_url)) {
                $faviconHtml = "<a href=\"{$this->config->project_url}\" class=\"avatar\">$faviconHtml</a>";
            }

            $faviconHtml = <<<HTML
				<div class="avatar">
					$faviconHtml
				</div>
HTML;
        } 

        $html = file_get_contents($this->config->dir_root.'/media/devoops/index.exakat.html');
        $html = str_replace('<menu>', $summaryHtml, $html);

        $html = str_replace('EXAKAT_VERSION', Exakat::VERSION, $html);
        $html = str_replace('EXAKAT_BUILD', Exakat::BUILD, $html);
        $html = str_replace('PROJECT_NAME', $this->config->project_name, $html);
        $html = str_replace('PROJECT_FAVICON', $faviconHtml, $html);

        file_put_contents($folder.'/'.$name.'/index.html', $html);
        
        foreach($summary as $titleUp => $section) {
            foreach($section as $title => $method) {
                if (method_exists($this, $method)) {
                    $html = $this->$method($title);
                } else {
                    $html = 'Using default for '.$title."\n";
                    display($html);
                }

                $filename = $this->makeFileName($title);
                
                $html = <<<HTML
<script language="javascript">
if (!document.getElementById("main")) {
    window.location.href = "../index.html#ajax/$filename";
}
</script >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Dashboard</a></li>
			<li><a href="#ajax/About-This-Report.html">About This Report</a></li>
		</ol>
	</div>
</div>

<h4 class="page-header">$title</h4>
<div class="row">
	<div class="col-xs-12">
$html
    </div>
</div>
HTML;
                
                file_put_contents($folder.'/'.$name.'/ajax/'.$filename, 
                                  $html);
            }
        }
        
        rename($folder.'/'.$name, $folder.'/'.$finalName);

        return '';
    }//end generate()
    
    protected function OneAnalyzer($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code', 'File', 'Line');
        $css->sort = $css->titles;

        $analyzer = $this->analyzers[$title];
        
        $description = $analyzer->getDescription()->getDescription();
        if ($description == '') {
            $description = 'No documentation yet';
        }
        $return = $this->formatTextLead($description);

        if ($clearPHP = $analyzer->getDescription()->getClearPHP()) {
            $return .= $this->formatText('clearPHP : <a href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$clearPHP.'.md">'.$clearPHP.'</a><br />', 'textLead');
        }

        $data = array();
        $sqlQuery = 'SELECT fullcode as Code, file AS File, line AS Line FROM results WHERE analyzer="'.$this->sqlite->escapeString($analyzer->getInBaseName()).'"';
        $res = $this->sqlite->query($sqlQuery);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        
        if (count($data) > 0) {
            $return .= $this->formatThemeList($analyzer->getThemes());
            $return .= $this->formatHorizontal($data, $css);
        } else {
            $return .= $this->formatText('The analyzer reported nothing after execution.');
        }
        
        return $return;
    }
}

?>