<?php
include ('../inc/connect.php');
session_start();
$keg = $_POST['keg'];
$jam = $_POST['jam'];
$tgl = DateTime::createFromFormat('m-d-Y', $_POST['tgl'])->format('Y-m-d');
$ustad = $_POST['ustad'];
$penyelenggara = $_POST['penyelenggara'];
$sql = "insert into detail_event values ('$tgl','$jam', '$ustad','$keg','$_SESSION[id]', '$penyelenggara')";

$sql = pg_query("$sql");

if ($sql){
	echo "<script>
		alert ('Data Successfully Added');
		eval(\"parent.location='../index1.php?page=listevent '\");
		</script>";
}else{
	echo 'error';
}
?>
