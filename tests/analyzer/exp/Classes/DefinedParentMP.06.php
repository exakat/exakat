<?php

$expected     = array('parent::$inTraitP',
                      'parent::inTrait(parent::$inTraitP)',
                     );

$expected_not = array('parent::$notInTraitP',
                      'parent::notInTrait(parent::$notInTraitP)',
                     );

?>