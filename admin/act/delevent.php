<?php
include ('../inc/connect.php');
	$id = $_GET['id'];
	$date = $_GET['date'];
	$jam = $_GET['jam'];
	$sql   = "delete from detail_event where time='$jam' and date='$date' and id_worship_place='$id'";

	$delete = pg_query($sql);
	if ($delete){
			echo "<script>
		alert (' Data Has Been Delete');
		eval(\"parent.location='../index1.php?page=listevent'\");
		</script>";
	}
	else{
		echo 'error';

	}

?>
