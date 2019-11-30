<?php

	include('connect.php');
    $latit = $_GET['lat'];
    $longi = $_GET['long'];
	  $rad=$_GET['rad'];

	$querysearch="SELECT id, name, address, capacity, st_x(st_centroid(geom)) as lng, st_y(st_centroid(geom)) as lat,
	CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) as jarak 
	FROM worship_place where CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) <= ".$rad."";

	$hasil=pg_query($querysearch);

    while($baris = pg_fetch_array($hasil))
        {
            $id=$baris['id'];
            $name=$baris['name'];
            $address=$baris['address'];
            $capacity=$baris['capacity'];
            $latitude=$baris['lat'];
            $longitude=$baris['lng'];
            $dataarray[]=array('id'=>$id,'name'=>$name,'address'=>$address,'capacity'=>$capacity,  "latitude"=>$latitude,"longitude"=>$longitude);
        }
        echo json_encode ($dataarray);
?>
