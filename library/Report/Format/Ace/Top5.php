<?php

namespace Report\Format\Ace;

class Top5 extends \Report\Format\Ace {
    static public $top5_counter = 0;
    
    private $title = '';
    private $columnsHeaders = array();
    
    public function render($output, $data) {
        $html = <<<HTML
							<div class="row-fluid">
								<div class="span5">
									<div class="widget-box transparent">
										<div class="widget-header widget-header-flat">
											<h4 class="lighter">
												{$data->getName()}
											</h4>

											<div class="widget-toolbar">
												<a href="#" data-action="collapse">
													<i class="icon-chevron-up"></i>
												</a>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main no-padding">
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
HTML;

        foreach($this->columnsHeaders as $columnHeader) {
            $html .= <<<HTML
															<th>
																name
															</th>

HTML;
        }
        
        $html .= <<<HTML
															</th>
														</tr>
													</thead>

													<tbody>
HTML;

        $values = $data->toArray();
        uasort($values, function ($a, $b) { if ($a['sort'] == $b['sort']) return 0 ; return $a['sort'] < $b['sort'] ? 1 : -1;});
        $values = array_slice($values, 0, 5);
        foreach($values as $value) {
            $html .= <<<HTML
														<tr>
															<td>{$value['name']}</td>

															<td>
																<b>{$value['count']}</b>
															</td>

															<td class="hidden-phone">
																<span class="label label-info arrowed-right arrowed-in">{$value['severity']}</span>
															</td>
														</tr>

HTML;
        }
        
        $html .= <<<HTML
													</tbody>
												</table>
											</div><!--/widget-main-->
										</div><!--/widget-body-->
									</div><!--/widget-box-->
								</div>
HTML;

        $output->push($html);
//        $output->pushToTheEnd($js);
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setColumnHeaders($columnsHeaders) {
        $this->columnsHeaders = $columnsHeaders;
    }
}

?>