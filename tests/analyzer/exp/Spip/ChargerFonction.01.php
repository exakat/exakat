<?php

$expected     = array("charger_fonction('envoyer_mail', 'inc2')",
                      "charger_fonction('envoyer_mail2', 'inc')",
                      "charger_fonction('inc_envoyer_mail2')",
                      "charger_fonction('envoyer_mail', 'in/c')",
                      "charger_fonction('exec_base_repair')",
                      "charger_fonction('inc_envoyer_mail')",
);

$expected_not = array("charger_fonction('get_infos','plugins')",
                      "charger_fonction('trouver_table','base', true)",
                      "charger_fonction('couleurs','inc')",
                      "charger_fonction('envoyer_mail','inc')",
                      "charger_fonction('traiter','formulaires/login')",
                      "charger_fonction('base_repair', 'exec')",
                      "charger_fonction('charger', \"formulaires/\$form\", true)",
                      "charger_fonction(\"editer_liens_oups_{\$table_source}_{\$objet}_{\$objet_lien}\",\"action\",true)",
);

?>