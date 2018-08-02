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

$id_tache = $_GET['id_tache'];
$query = "SELECT * FROM sous_taches WHERE id_tache = '".$id_tache."' ";
$result = $db->query($query) or die($db->error);
$nb = mysqli_num_rows($result);
if($nb != 0) {
?>
<div class="form-group">
 <label for="traitant">Selection multiple Sous-Tâches (Cmd ou Ctrl)</label>
	<select multiple class="custom-select" id="sous_taches[]" name="sous_taches[]">
		 <?php
			while($row = mysqli_fetch_array($result)) {
				?>
				 <option selected value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option><?php
			}
			?>
 </select>
</div>
<?php
} ?>
