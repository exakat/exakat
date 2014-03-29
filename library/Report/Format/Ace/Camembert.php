<?php

namespace Report\Format\Ace;

class Camembert extends \Report\Format\Ace { 
    public function render($output, $data) {
        $html = <<<HTML
								<div class="span5">
									<div class="widget-box">
										<div class="widget-header widget-header-flat widget-header-small">
											<h5>
												<i class="icon-signal"></i>
												Traffic Sources
											</h5>

											<div class="widget-toolbar no-border">
												<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
													This Week
													<i class="icon-angle-down icon-on-right"></i>
												</button>

												<ul class="dropdown-menu dropdown-info pull-right dropdown-caret">
													<li class="active">
														<a href="#">This Week</a>
													</li>

													<li>
														<a href="#">Last Week</a>
													</li>

													<li>
														<a href="#">This Month</a>
													</li>

													<li>
														<a href="#">Last Month</a>
													</li>
												</ul>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<div id="piechart-placeholder"></div>

												<div class="hr hr8 hr-double"></div>

												<div class="clearfix">
													<div class="grid3">
														<span class="grey">
															<i class="icon-facebook-sign icon-2x blue"></i>
															&nbsp; likes
														</span>
														<h4 class="bigger pull-right">1,255</h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="icon-twitter-sign icon-2x purple"></i>
															&nbsp; tweets
														</span>
														<h4 class="bigger pull-right">941</h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="icon-pinterest-sign icon-2x red"></i>
															&nbsp; pins
														</span>
														<h4 class="bigger pull-right">1,050</h4>
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

        $js = <<<'JS'

				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:$(this).data('min') || 0} );
				});
			
			
			
			
			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "social networks",  data: 38.7, color: "#68BC31"},
				{ label: "search engines",  data: 24.5, color: "#2091CF"},
				{ label: "ad campaings",  data: 8.2, color: "#AF4E96"},
				{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
				{ label: "other",  data: 10, color: "#FEE074"}
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  $.plot(placeholder, data, {
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