<?php
include('Connect.php');
$latit=$_GET["lat"];
$longi=$_GET["lng"];
$rad=$_GET["rad"];


$querysearch="SELECT ow_id, ow_nama,ow_tiket, st_x(st_centroid(the_geom)) as lon,st_y(st_centroid(the_geom)) as lat,
	CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(objek_wisata.geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) as jarak
	FROM objek_wisata where CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(objek_wisata.geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) <= ".$rad."
			 ";
$hasil=pg_query($querysearch);
while($row = pg_fetch_array($hasil))
	{
		  $ow_id=$row['ow_id'];
		  $ow_nama=$row['ow_nama'];
		  $ow_tiket=$row['ow_tiket'];
		  $longitude=$row['lon'];
		  $latitude=$row['lat'];
		  $dataarray[]=array('ow_id'=>$ow_id,'ow_nama'=>$ow_nama,'ow_tiket'=>$ow_tiket,
		  'longitude'=>$longitude,'latitude'=>$latitude);
	}
echo json_encode ($dataarray);
?>
