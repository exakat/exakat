$(document).ready(function() {
	$('[data-toggle="toggle"]').change(function(){
		$(this).parents().next('.hide').toggle();
	});
	$('[data-toggle="toggle"]').parents().next('.hide').toggle();
});