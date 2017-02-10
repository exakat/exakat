<?php 				

function foo() {
call_user_func(function() {
	ob_start() ?>A<?= $c ?>B<?= $d ?> {
			}
		}
	<?php $a++;
});

}

