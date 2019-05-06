<?php
$data = array(
    array(-1, 1 => 0.43, 3 => 0.12, 9284 => 0.2),
    array(1, 1 => 0.22, 5 => 0.01, 94 => 0.11),
);

$svm = new SVM();
$model = $svm->train($data);

$data = array(1 => 0.43, 3 => 0.12, 9284 => 0.2);
$result = $model->predict($data);
var_dump($result);
$model->save('model.svm');

$svn = new SVN();
?>