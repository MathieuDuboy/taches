<?php
include('config.php');
$nom = $_GET['nom'];
echo $nom.'<br />';

$id_manager = $_GET['id_manager'];
$manager = $_GET['manager'];
echo $id_manager.'<br />';
$id_client = $_GET['id_client'];
$client = $_GET['client'];
echo $id_client.'<br />';
$id_tech = $_GET['id_tech'];
$tech = $_GET['tech'];
echo $id_tech.'<br />';

$date_debut = $_GET['date_debut'];
echo $date_debut.'<br />';
$date_installation = $_GET['date_installation'];
echo $date_installation.'<br />';
$now = time();

// requete pour ajouter
//
$sql    = "INSERT INTO `projets` (`id`, `nom`, `date_debut`, `date_installation`, `id_manager`, `manager`, `id_client`, `client`, `id_tech`, `tech`, `etat`, `last_modification`) VALUES (NULL, '".$nom."', '".$date_debut."', '".$date_installation."', '".$id_manager."', '".$manager."', '".$id_client."','".$client."', '".$id_tech."','".$tech."', 'Initialisé', '".$now."');";
$result = mysqli_query($db, $sql);


?>
