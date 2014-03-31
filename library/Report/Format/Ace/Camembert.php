<?php

namespace Report\Format\Ace;

class Camembert extends \Report\Format\Ace {
    static public $camembert_counter = 0;
    
    public function render($output, $data) {
        $jsData = "";
        $colors = array("#68BC31", "#2091CF", "#AF4E96", "#DA5430", "#FEE074", "#CE6F9E", );
        $i = 0;
        $array = $data->toArray(); 

        $keys = array_keys($array);
        $values = array_values($array);
        foreach($values as $k => $v) {
            $values[$k] = number_format($v / array_sum($array) * 100, 0)." %";
        }
        
        foreach($array as $k => $v) {
            $i++;
            $jsData .= "				{ label: \"$k\",  data: $v, color: \"".$colors[$i % count($colors)]."\"},\n";
        }
        
        $title = $data->getName();
        
        $counter = \Report\Format\Ace\Camembert::$camembert_counter++;
        
        $html = <<<HTML
								<div class="span5">
									<div class="widget-box">
										<div class="widget-header widget-header-flat widget-header-small">
											<h5>
												 {$title}
											</h5>

											<div class="widget-toolbar no-border">
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<div id="piechart-placeholder-{$counter}"></div>

												<div class="hr hr8 hr-double"></div>

												<div class="clearfix">
													<div class="grid3">
														<span class="grey">
															<i class="icon-wrench icon-2x blue"></i>
															&nbsp; {$keys[0]}
														</span>
														<h4 class="bigger pull-right">{$values[0]}</h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="icon-wrench icon-2x purple"></i>
															&nbsp; {$keys[1]}
														</span>
														<h4 class="bigger pull-right">{$values[1]}</h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="icon-wrench icon-2x red"></i>
															&nbsp; {$keys[2]}
														</span>
														<h4 class="bigger pull-right">{$values[2]}</h4>
													</div>
												</div>
											</div><!--/widget-main-->
										</div><!--/widget-body-->
									</div><!--/widget-box-->
								</div><!--/span-->
							</div><!--/row-->
    
HTML;

        $output->pushToJsLibraries( array("assets/js/jquery-ui-1.10.3.custom.min.js",
                                          "assets/js/jquery.ui.touch-punch.min.js",
                                          "assets/js/jquery.slimscroll.min.js",
                                          "assets/js/jquery.easy-pie-chart.min.js",
                                          "assets/js/jquery.sparkline.min.js",
                                          "assets/js/flot/jquery.flot.min.js",
                                          "assets/js/flot/jquery.flot.pie.min.js",
                                          "assets/js/flot/jquery.flot.resize.min.js",
                                    ));

        $js = <<<JS

				\$('.easy-pie-chart.percentage').each(function(){
					var \$box = \$(this).closest('.infobox');
					var barColor = \$(this).data('color') || (!\$box.hasClass('infobox-dark') ? \$box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt(\$(this).data('size')) || 50;
					\$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})
			
				\$('.sparkline').each(function(){
					var \$box = \$(this).closest('.infobox');
					var barColor = !\$box.hasClass('infobox-dark') ? \$box.css('color') : '#FFF';
					\$(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:\$(this).data('min') || 0} );
				});
			
			
			
			
			  var placeholder = \$('#piechart-placeholder-{$counter}').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
			  {$jsData}
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  \$.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);

JS;

        $output->push($html);
        $output->pushToTheEnd($js);
    }
}

?>