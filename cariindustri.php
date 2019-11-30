<?php

	include('connect.php');
    $latit = $_GET['lat'];
    $longi = $_GET['long'];
	  $rad=$_GET['rad'];

	$querysearch="SELECT id, name, owner, cp, address, id_industry_type, employee, st_x(st_centroid(geom)) as lng, st_y(st_centroid(geom)) as lat,
	CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) as jarak
	FROM small_industry where CAST(ST_DistanceSpheroid(ST_GeomFromText('POINT($longi $latit)',-1),ST_Centroid(geom),'SPHEROID[\"WGS 84\",6378137,298.257223563]') As numeric) <= ".$rad."";

	$hasil=pg_query($querysearch);

        while($baris = pg_fetch_array($hasil))
            {
                $id=$baris['id'];
                $name=$baris['name'];
                $owner=$baris['owner'];
                $cp=$baris['cp'];
                $address=$baris['address'];
                $id_industry_type=$baris['id_industry_type'];
                $employee=$baris['employee'];
                $latitude=$baris['lat'];
                $longitude=$baris['lng'];
                $dataarray[]=array('id'=>$id,'name'=>$name,'owner'=>$owner,'cp'=>$cp, 'address'=>$address, 'id_industry_type'=>$id_industry_type, 'employee'=>$employee,  "latitude"=>$latitude,"longitude"=>$longitude);
            }
            echo json_encode ($dataarray);
?>
