<?php
//Database Connection file. Update with your Database information once you create database from cpanel, or mysql.
define("DB_HOST", "localhost"); //Databse Host.
define("DB_USER", "of2ds84i_robert"); //Databse User.
define("DB_PASS", "Pm7xojnz"); //database password.
define("DB_NAME", "of2ds84i_wp587"); //database Name.

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_errno > 0) {
    die('Unable to connect to database 1 [' . $db->connect_error . ']');
}
$db->set_charset("utf8");

$query = "SELECT id,nom,'NC' AS id_tache from taches UNION ALL SELECT id,nom,id_tache from sous_taches";
$result = $db->query($query) or die($db->error);
$content = '';
$count = 0;

$tab = [];
while($row = $result->fetch_array()) {
  extract($row);
	if($id_tache == 'NC') {
		$val = 'Tâche ##'.$id.' :: '.$nom;
	} else  {
		$val = 'Sous-tâche ##'.$id.' :: '.$nom;
	}
  array_push($tab, $val);

}//loop ends here.
echo json_encode($tab);

?>
