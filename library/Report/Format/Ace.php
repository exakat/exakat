<?php

namespace Report\Format;

class Ace extends \Report\Format { 
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
        
        $this->format = 'Ace';
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
        mkdir($dir.'/pages/', 0755);
        print shell_exec('cp -r media/ace-admin/assets '.$dir.'/pages/');
        
        $total = 0;
        foreach($this->files as $name => $html) {
            $total += file_put_contents($dir.'/pages/'.$name, $html);
        }
        
        $index_html = <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">    
	<head>
        <meta http-equiv="refresh" content="0; url=pages/Code-smells.html" />
		<meta name="description" content="Exakat Audit report. © 2014 Exakat" />
	</head>
	<body>
	</body>
</html>

HTML;
        file_put_contents($dir.'/index.html', $index_html);
        
        shell_exec('cd '.dirname($dir).'; zip -r '.basename($dir).' '.basename($dir).' 2 >> /dev/null'); 
        
        return $total;
    }
    
    protected function toFile2($filename, $data) {
        $section_name = $data->getName();
        
        $renderSidebar = new \Report\Format\Ace\SummarySidebar();
        $sidebar = new static();
        
        if ($this->summary === null) {
            $sidebar = '<!-- No sidebar -->';
        } else {
            $renderSidebar->render($sidebar, $this->summary->getContent());
            $sidebar = $sidebar->getOutput();
        }
        
        if (count($this->jsLibraries) > 0) {
            $this->jsLibraries = array_keys(array_count_values(($this->jsLibraries)));
            $this->jsLibraries = "        <script src=\"".implode("\"></script>\n        <script src=\"", $this->jsLibraries)."\"></script>\n";
        } else {
            $this->jsLibraries = "<!-- No extra libraries -->";
        }

        $sidebar = <<<HTML
			<div class="sidebar" id="sidebar">
{$sidebar}

				<div class="sidebar-collapse" id="sidebar-collapse">
					<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>
HTML;

$project_name = $this->projectName;
$project_code_source = $this->projectUrl;

if (empty($project_code_source)) {
    $project_code_source_html = "";
} else {
    $project_code_source_html = "<a href=\"$project_code_source\" class=\"brand\">( $project_code_source )</a>";
}
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Exakat Audit Report</title>


		<meta name="description" content="Exakat Audit report. © 2014 Exakat" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="shortcut icon" href="http://www.exakat.io/wp-content/themes/alterna/img/favicon.png" />

		<!--basic styles-->

		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--page specific plugin styles-->

		<!--fonts-->

		<link rel="stylesheet" href="assets/css/ace-fonts.css" />

		<!--ace styles-->

		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!--inline styles related to this page-->

		<!--ace settings handler-->

		<script src="assets/js/ace-extra.min.js"></script>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-51182320-1', 'exakat.io');
          ga('send', 'pageview');

        </script>
	</head>

	<body>
		<div class="navbar" id="navbar">

			<div class="navbar-inner">
				<div class="container-fluid">
					<small>
	    				<a href="Code-smells.html" class="brand">
							<img src="assets/img/logo-exakat.png" height="32" width="100" />
							Exakat Audit Report for  : $project_name
                        </a>$project_code_source_html
					</small><!--/.brand-->
				</div><!--/.container-fluid-->
			</div><!--/.navbar-inner-->
		</div>

		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

$sidebar

			<div class="main-content">
				<div class="page-content">

					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->
							<div class="row-fluid">
								<h1 class="header smaller lighter blue">$section_name</h1>
{$this->output}
				</div><!--/.page-content-->

				<div class="ace-settings-container" id="ace-settings-container">
					<div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
						<i class="icon-cog bigger-150"></i>
					</div>

					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="default" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; Choose Skin</span>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>
					</div>
				</div><!--/#ace-settings-container-->
			</div><!--/.main-content-->
		</div><!--/.main-container-->

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only bigger-110"></i>
		</a>

		<!--basic scripts-->

		<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!--page specific plugin scripts-->

    	{$this->jsLibraries}

		<!--ace scripts-->

		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

		<!--inline scripts related to this page-->

		<script type="text/javascript">
			jQuery(function($) {
    			{$this->finalJs}
			})
			
		</script>
	</body>
</html>

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

        $this->css = new \Report\Format\Css($css, $shortClass);
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

    protected function makeLink($title) {
        $file = $this->makeFileName($title);
        return "<a href=\"$file\">$title</a>";
    }
}

?>
