<?php

$expected     = array('enchant_broker_init( )',
                      'enchant_broker_describe($r)',
                      'enchant_broker_list_dicts($r)',
                      'enchant_broker_dict_exists($r, $tag)',
                      'enchant_broker_request_dict($r, $tag)',
                      'enchant_dict_describe($d)',
                      'enchant_dict_check($d, "soong")',
                      'enchant_dict_suggest($d, "soong")',
                      'enchant_broker_free_dict($d)',
                      'enchant_broker_free($r)',
                     );

$expected_not = array('enchant_broker_free($r2)',
                     );

?>