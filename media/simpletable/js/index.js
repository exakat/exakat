$(document).ready(function() {
	$('[data-toggle="toggle"]').change(function(){
		$(this).parents().next('.hide').toggle();
	});
});