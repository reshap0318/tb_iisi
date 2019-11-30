<?php
include('connect.php');
$latit=$_GET["lat"];
$longi=$_GET["lng"];
$rad=$_GET["rad"];

$querysearch="SELECT id, name,st_x(st_centroid(geom)) as lon,st_y(st_centroid(geom)) as lat,
	CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(worship_place.geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) as jarak
	FROM worship_place where CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(worship_place.geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) <= ".$rad." ORDER BY jarak
			 ";
$hasil=pg_query($querysearch);
while($row = pg_fetch_array($hasil))
	{
		  $id=$row['id'];
		  $name=$row['name'];
		  $longitude=$row['lon'];
		  $latitude=$row['lat'];
		  $dataarray[]=array('id'=>$id,'name'=>$name,
		  'longitude'=>$longitude,'latitude'=>$latitude);
	}
echo json_encode ($dataarray);
?>
