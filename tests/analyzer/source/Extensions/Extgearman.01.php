<?php

# Crée un nouveau client.
$gmclient= new GearmanClient();

# Ajoute un serveur par défaut (localhost).
$gmclient->addServer();

echo "Envoie du travail\n";

# Envoie le travail d'inversion
do
{
  $result = $gmclient->do("reverse", "Hello!");

  # Vérifie les paquets et les erreurs retournés.
  switch($gmclient->returnCode())
  {
    case GEARMAN_WORK_DATA:
      echo "Données : $result\n";
      break;
    case GEARMAN_WORK_STATUS:
      list($numerator, $denominator)= $gmclient->doStatus();
      echo "Statut : $numerator/$denominator complete\n";
      break;
    case GEARMAN_WORK_FAIL:
      echo "Échec\n";
      exit;
    case GEARMAN_SUCCESS:
      echo "Succès: $result\n"; 
      break;
    default:
      echo "Code retourné : " . $gmclient->returnCode() . "\n";
      exit;
  }
}
while($gmclient->returnCode() != GEARMAN_SUCCESS);

?>