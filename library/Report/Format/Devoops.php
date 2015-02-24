<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Format;

class Devoops extends \Report\Format { 
    private $output = '';
    private $finalJs = '';
    private $jsLibraries = array();
    
    private $files = array();
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension = "html";
    
    protected $css = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->format = 'Devoops';
    }

    public function render($output, $data) {
        // default behavior
        $output->push("Text for ".get_class($this)." (".strlen($data)." bytes).\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function pushToTheEnd($render) {
        $this->finalJs .= "$render\n";
    }

    public function pushToJsLibraries($library) {
        if (is_array($library)) {
            $this->jsLibraries = array_merge($this->jsLibraries, $library);
        } else {
            $this->jsLibraries[] = $library;
        }
    }

    public function reset() {
        $this->output  = "";
        $this->finalJs = "";
        $this->jsLibraries = array();
    }
    
    public function setSummaryData($data) {
        $this->summary = $data;
    }
    
    public function toFile($filename) {
        $ext = $this->getExtension();
        $dir = substr($filename, 0, - (1 + strlen($ext)));
        if (file_exists($dir)) {
            shell_exec("rm -rf $dir"); 
        }
        mkdir($dir, 0755);
        mkdir($dir.'/ajax/', 0755);
print        shell_exec('cp -r media/devoops/css '.$dir.'/');
print        shell_exec('cp -r media/devoops/img '.$dir.'/');
print        shell_exec('cp -r media/devoops/js '.$dir.'/');
print        shell_exec('cp -r media/devoops/plugins '.$dir.'/');

        // building the summary in the index.html file
        $renderSidebar = new \Report\Format\Devoops\SummarySidebar();
        $sidebar = new static();
        
        if ($this->summary === null) {
            $sidebar = '<!-- No sidebar -->';
        } else {
            $renderSidebar->render($sidebar, $this->summary->getContent());
            $sidebar = $sidebar->getOutput();
        }
        
        $html = file_get_contents('media/devoops/index.exakat.html');
        $html = str_replace('<menu>', $sidebar, $html);
        file_put_contents($dir.'/index.html', $html);
        
        // writing the content files in the ajax folder
        $total = 0;
        foreach($this->files as $name => $html) {
            $total += file_put_contents($dir.'/ajax/'.$name, $html);
        }
        
        $index_html = <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">    
	<head>
        <meta http-equiv="refresh" content="0; url=pages/Code-smells.html" />
		<meta name="description" content="Exakat Audit report. © 2014 - 2015 Exakat" />
	</head>
	<body>
	</body>
</html>

HTML;
        
        // @todo : check that ZIP is available
        // @todo support other format for archiving
        shell_exec('cd '.dirname($dir).'; zip -r '.basename($dir).' '.basename($dir).' 2 >> /dev/null'); 
        
        return $total;
    }
    
    protected function toFile2($filename, $data) {
        $section_name = $this->makeHtml($data->getName());
        
        if (count($this->jsLibraries) > 0) {
            $this->jsLibraries = array_keys(array_count_values(($this->jsLibraries)));
            $this->jsLibraries = "        <script src=\"".implode("\"></script>\n        <script src=\"", $this->jsLibraries)."\"></script>\n";
        } else {
            $this->jsLibraries = "<!-- No extra libraries -->";
        }

        $project_name = $this->projectName;
        $project_code_source = $this->projectUrl;

        if (empty($project_code_source)) {
            $project_code_source_html = "";
        } else {
            $project_code_source_html = "<a href=\"$project_code_source\" class=\"brand\">( $project_code_source )</a>";
        }
        
        $breadcrumb =  <<<TEXT
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Dashboard</a></li>
			<li><a href="#ajax/$filename">$section_name</a></li>
		</ol>
	</div>
</div>

TEXT;
        
        $html = <<<HTML
$breadcrumb
<h4 class="page-header">$section_name</h4>
<div class="row">
	<div class="col-xs-12">
        {$this->output}
	</div>
</div>

HTML;
        $this->files[$filename] = $html;

        $this->reset();
        
        return true;
    }

    public function setAnalyzer($name) {
        self::$analyzer = $name;
    }

    public function setCss($css) {
        $class = explode('\\', get_class($this));
        $shortClass = array_pop($class);

        $this->css = new \Report\Format\Devoops\Css($css, $shortClass);
    }
    
    public function getOutput() {
        return $this->output;
    }

    protected function makeFileName($title) {
        // must sync with Template/Section.php
        // @todo : remove this sync!
        return str_replace(array(' ', '(', ')', ':', '*', '.', '/' ), 
                           array('-', '' , '' , '' , '' , '', '' ),
                               $title).'.html';
    }

    protected function makeLink($title, $file = null) {
        if ($file == null) {
            $file = $this->makeFileName($title);
        }
        $title = $this->makeHtml($title);
        return "<a href=\"$file\" class=\"exakat-link\">$title</a>";
    }
    
    protected function makeHtml($text) {
        return nl2br(trim(htmlentities($text, ENT_COMPAT | ENT_HTML401 , 'UTF-8')));
    }
}

?>
