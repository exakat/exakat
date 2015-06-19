<?php

// won't find (dynamic)
charger_fonction('charger', "formulaires/$form", true);
if ($oups_objets = charger_fonction("editer_liens_oups_{$table_source}_{$objet}_{$objet_lien}","action",true)){ 2; }

// real code
$get_infos =      charger_fonction('get_infos','plugins');
$trouver_table =  charger_fonction('trouver_table','base', true);
$couleurs =       charger_fonction('couleurs','inc');
$envoyer_mail =   charger_fonction('envoyer_mail','inc');
$traiter =        charger_fonction('traiter','formulaires/login');
$traiter =        charger_fonction('base_repair', 'exec');

// real errors
$traiter =        charger_fonction('exec_base_repair');
$erroneous =      charger_fonction('envoyer_mail','inc2');
$erroneous2 =     charger_fonction('envoyer_mail2','inc');
$erroneous3 =     charger_fonction('inc_envoyer_mail2');
$erroneous4 =     charger_fonction('envoyer_mail','in/c');
$erroneous5 =     charger_fonction('inc_envoyer_mail'); // default is exec

?>