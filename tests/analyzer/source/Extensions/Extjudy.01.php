<?php 
$judy = new Judy(Judy::BITSET);
if ($judy->getType() === judy_type($judy) &&
    $judy->getType() === Judy::BITSET) {
    echo "Judy BITSET type OK\n";
} else {
    echo "Judy BITSET type check fail\n";
}
unset($judy);

echo JUDY;

?>