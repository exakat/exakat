<?php

foreach ($arr as &$value_unset) {
    $value_unset = $value_unset * 2;
}
unset($value_unset);

foreach ($arr as $k => &$value_unset2) {
    $value_unset2 = $value_unset2 * 2;
}
unset($value_unset2);


foreach ($arr as &$value_not_unset) {
    $value_not_unset = $value_not_unset * 2;
}

foreach ($arr as $k => &$value_not_unset2) {
    $value_not_unset2 = $value_not_unset2 * 2;
}

foreach ($arr as &$value_unset_other) {
    $value_unset_other = $value_unset_other * 2;
}
unset($value_unset_other2);

foreach ($arr as $value_not_reference) {
    $value_not_reference = $value_not_reference * 2;
}

?>