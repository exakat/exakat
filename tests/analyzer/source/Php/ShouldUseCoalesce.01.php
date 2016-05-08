<?php 

isset($a) ? $a : 'b';
!isset($a) ? 'b' : $a;

$a->f === NULL ? $a->f : 'b';
nuLL === $a->f ? $a->f : 'b';
nuLL !== $a->f ? 'b' : $a->f ;

if (($model = Model::get($id)) === NULL) { $model = $default_model; }
if ( NULL === ($model = Model::get($id))) { $model = $default_model; }

isset($a) ? $b : 'b';
isset($a) ? 'b' : $a;

$a->f == NULL ? $a->f : 'b';

?>