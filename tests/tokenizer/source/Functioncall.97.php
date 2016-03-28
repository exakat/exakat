<?php echo B('C', D(function ($b) { return 'E' . $b['F'] . 'E'; }, $c)); ?>
<?php echo H('I', [B('C', D(function ($b) { return 'E' . $b['F'] . 'E'; }, $c)), 1,2]); ?>
<?php echo H('I', [B('C', D(function ($b) { echo 'E' , $b['F'] , 'E'; }, $c)), 1,2]); ?>
