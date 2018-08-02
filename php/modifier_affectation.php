<?php
include('config.php');

$id_projet = $_GET['detail_id_projet'];
$detail_id_affectation = $_GET['detail_id_affectation'];
$detail_date_max = $_GET['detail_date_max'];
$detail_traitant = $_GET['detail_traitant'];
$detail_notes = $_GET['detail_notes'];

$date = explode("/", $detail_date_max);
$detail_date_max_timestamp = strtotime($date[2].'-'.$date[1].'-'.$date[0]);

// trait
$sql = "UPDATE `affectation` SET `date_max` = '".$detail_date_max."', `date_max_timestamp` = '".$detail_date_max_timestamp."', `traitant` = '".$detail_traitant."' WHERE `affectation`.`id` = '".$detail_id_affectation."' ;";
$result = mysqli_query($db, $sql);
// add note

$sql_note = "INSERT INTO `notes_taches` (`id`, `id_tache`, `date_ajout`, `note`) VALUES (NULL, '".$detail_id_affectation."', '".$now."', '".$detail_notes."');";
$result = mysqli_query($db, $sql_note);

$sql_update = "UPDATE `projets` SET `last_modification` = '".$now."' WHERE `projets`.`id` = $id_projet;";
$result = mysqli_query($db, $sql_update);


?>
