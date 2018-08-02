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
$type_de_recherche = $_GET['type'];
$recherche = $_GET['recherche'];
//echo $client;

global $db;
global $label_obj;
if($type_de_recherche == 'manager') {
	$query = "SELECT * from collab INNER JOIN users ON collab.collab_clientid = users.user_id WHERE users.user_type = 'admin'";
}else if($type_de_recherche == 'client') {
	$query = "SELECT * from collab INNER JOIN users ON collab.collab_clientid = users.user_id WHERE users.user_type = 'client'";
}
else if($type_de_recherche == 'tech') {
	$query = "SELECT * from collab INNER JOIN users ON collab.collab_clientid = users.user_id WHERE collab.collab_t = 1";
}
$result = $db->query($query) or die($db->error);
$content = '';
$count = 0;

$tab = [];

while($row = $result->fetch_array()) {
  extract($row);
  $val = $collab_name.' '.$collab_pname.' / '.$company.' : '.$collab_mail.' ##Id::'.$collab_id.' - ##cid:'.$collab_clientid;
  array_push($tab, $val);

}//loop ends here.
echo json_encode($tab);

?>
